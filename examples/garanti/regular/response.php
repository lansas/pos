<?php

require '_config.php';

require '../../template/_header.php';

if ($request->getMethod() !== 'POST') {
    echo new \Symfony\Component\HttpFoundation\RedirectResponse($base_url);
    exit();
}

$order_id = date('Ymd') . strtoupper(substr(uniqid(sha1(time())),0,4));
$amount = (double) 100;

$order = [
    'id'            => $order_id,
    'name'          => 'John Doe', // optional
    'email'         => 'mail@customer.com', // optional
    'user_id'       => '12', // optional
    'amount'        => $amount,
    'installment'   => '0',
    'currency'      => 'TRY',
    'ip'            => $ip,
    'transaction'   => 'pay', // pay => Auth, pre PreAuth
];

try {
    $pos->prepare($order);
} catch (\Mews\Pos\Exceptions\UnsupportedTransactionTypeException $e) {
    var_dump($e->getCode(), $e->getMessage());
}

$card = [
    'number'    => $request->get('number'),
    'month'     => $request->get('month'),
    'year'      => $request->get('year'),
    'cvv'       => $request->get('cvv'),
];

$payment = $pos->payment($card);

$response = $payment->response;

$dump = get_object_vars($response);
?>

<div class="result">
    <h3 class="text-center text-<?php echo $response->code == '00' ? 'success' : 'danger'; ?>">
        <?php echo $response->code == '00' ? 'Payment is successful!' : 'Payment is not successful!'; ?>
    </h3>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">Response:</dt>
        <dd class="col-sm-9"><?php echo $response->response; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">Status:</dt>
        <dd class="col-sm-9"><?php echo $response->status; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">Transaction:</dt>
        <dd class="col-sm-9"><?php echo $response->transaction; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">Transaction Type:</dt>
        <dd class="col-sm-9"><?php echo $response->transaction_type; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">Order ID:</dt>
        <dd class="col-sm-9"><?php echo $response->order_id ? $response->order_id : '-'; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">Group ID:</dt>
        <dd class="col-sm-9"><?php echo $response->group_id ? $response->group_id : '-'; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">AuthCode:</dt>
        <dd class="col-sm-9"><?php echo $response->auth_code ? $response->auth_code : '-'; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">HostRefNum:</dt>
        <dd class="col-sm-9"><?php echo $response->host_ref_num ? $response->host_ref_num : '-'; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">ProcReturnCode:</dt>
        <dd class="col-sm-9"><?php echo $response->code; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">Error Code:</dt>
        <dd class="col-sm-9"><?php echo $response->error_code ? $response->error_code : '-'; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-3">Error Message:</dt>
        <dd class="col-sm-9"><?php echo $response->error_message ? $response->error_message : '-'; ?></dd>
    </dl>
    <hr>
    <dl class="row">
        <dt class="col-sm-12">All Data Dump:</dt>
        <dd class="col-sm-12">
            <pre><?php print_r($dump); ?></pre>
        </dd>
    </dl>
    <hr>
    <div class="text-right">
        <a href="index.php" class="btn btn-lg btn-info">&lt; Click to payment form</a>
    </div>
</div>

<?php require '../../template/_footer.php'; ?>
