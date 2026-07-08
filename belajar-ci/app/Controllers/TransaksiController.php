<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\RajaOngkirService;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
class TransaksiController extends BaseController
{
protected $cart;
protected $transactionModel;
protected $transactionDetailModel;

public function __construct()
{
    helper(['number', 'form']);
    $this->cart = service('cart');
    $this->transactionModel = new TransactionModel();
    $this->transactionDetailModel = new TransactionDetailModel(); 
}
    
    public function index()
{  
    $data = [
        'items' => $this->cart->contents() ,
        'total' => $this->cart->total() 
    ];

    return view('v_keranjang', $data);
}

public function cart_add()
{
	$this->cart->insert([
	    'id'      => $this->request->getPost('id'),
	    'qty'     => 1,
	    'price'   => $this->request->getPost('harga'),
	    'name'    => $this->request->getPost('nama'),
	    'options' => [
	        'foto' => $this->request->getPost('foto')
	    ]
	]);
	
	session()->setFlashdata(
	    'success',
	    'Produk berhasil ditambahkan ke keranjang. 
	    <a href="' . base_url('keranjang') . '">Lihat</a>'
	);
	
	return redirect()->to(base_url('/'));
} 
public function cart_edit()
{
    $i = 1;
    foreach ($this->cart->contents() as $item) {
        $qty = $this->request->getPost('qty' . $i++);

        $this->cart->update([
            'rowid' => $item['rowid'],
            'qty'   => $qty
        ]);
    }

    session()->setFlashdata(
        'success',
        'Keranjang berhasil diperbarui'
    );

    return redirect()->to(base_url('keranjang'));
}
public function cart_delete($rowid)
{
    $this->cart->remove($rowid);

    session()->setFlashdata(
        'success',
        'Produk berhasil dihapus dari keranjang'
    );

    return redirect()->to(base_url('keranjang'));
}
public function cart_clear()
{
    $this->cart->destroy();

    session()->setFlashdata(
        'success',
        'Keranjang berhasil dikosongkan'
    );

    return redirect()->to(base_url('keranjang'));
}
public function checkout()
{  
    $total_keranjang = $this->cart->total();
    $data = [
        'items'       => $this->cart->contents(),
        'total'       => $total_keranjang,
    ];

    return view('v_checkout', $data);
}
public function destinations()
{
    $search = $this->request->getGet('q'); 

    $results = [
        'id'   => $search,
        'text' => $search
    ];
    $service = new RajaOngkirService();
$response = $service->getDestination($search);

$results = [];
$data = $response['data'] ?? [];

foreach ($data as $item) {
    $results[] = [
        'id'   => $item['id'],
        'text' => $item['label']
    ];
}

    return $this->response->setJSON([
        'results' => $results
    ]);
}
public function costs()
{
    $origin = '64999';
    $destination = $this->request->getGet('destination');
    $weight = '1000';
    $courier = 'jne'; 

    $service = new RajaOngkirService();
    $response = $service->getCost($origin, $destination, $weight, $courier);

    $results = [];
    $data = $response['data'] ?? [];

    foreach ($data as $item) {
        $results[] = [
            'service'     => $item['service'],
            'description' => $item['description'],
            'cost'        => $item['cost'],
            'etd'         => $item['etd']
        ];
    }

    return $this->response->setJSON($results);
}
public function buy()
{ 
    require_once APPPATH . 'Helpers/PromoHelper.php';
    
    $cartItems = $this->cart->contents();
    if (empty($cartItems)) {
        return redirect()->back();
    }

    $db = \Config\Database::connect();
    $db->transStart(); 

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['qty'] * $item['price'];
    }

    $ongkir = (int) $this->request->getPost('ongkir');
    $voucher_code = $this->request->getPost('voucher_code'); 

    $biaya_jasa = hitung_biaya_jasa($subtotal);
    $diskon_voucher = hitung_diskon_voucher($subtotal, $voucher_code);
    $free_mouse = hitung_free_mouse($subtotal);

    $total_harga = ($subtotal - $diskon_voucher - $free_mouse) + $biaya_jasa + $ongkir;

    $data_transaksi = [
            'username'       => $this->request->getPost('username'),
            'total_harga'    => $total_harga,
            'alamat'         => $this->request->getPost('alamat'),
            'ongkir'         => $ongkir,
            'status'         => 0,
            'biaya_jasa'     => $biaya_jasa,
            'voucher_code'   => $voucher_code,
            'diskon_voucher' => $diskon_voucher,
            'free_mouse'     => $free_mouse
        ];
    if (!$this->transactionModel->insert($data_transaksi)) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Gagal membuat transaksi');
    }

    $transactionId = $this->transactionModel->getInsertID();

    foreach ($cartItems as $item) {
        $this->transactionDetailModel->insert([
            'transaction_id' => $transactionId,
            'product_id'     => $item['id'],
            'jumlah'         => $item['qty'],
            'diskon'         => 0, 
            'subtotal_harga' => $item['qty'] * $item['price'] 
        ]);
    }

    $db->transComplete();

    if (!$db->transStatus()) {
        return redirect()->back()->with('error', 'Gagal membuat transaksi');
    }

    $this->cart->destroy();
    return redirect()->to(base_url());
}
public function history()
{
    $username = session()->get('username'); 
 
    $transactions = $this->transactionModel->where('username', $username)->findAll();
    $transactionIds = array_column($transactions, 'id');

    $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);

    $data = [
        'username'      => $username,
        'transactions'  => $transactions,
        'products'      => $products
    ]; 

    return view('v_history', $data);
}
}
