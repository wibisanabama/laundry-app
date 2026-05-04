<?php
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;

$user = User::first();
$c = Customer::create(['name' => 'Delete Cascade Test']);
Order::create(['customer_id' => $c->id, 'user_id' => $user->id, 'status' => 'pending']);

echo "Created customer ID: " . $c->id . " with orders.\n";

try {
    $c->delete();
    echo "Deleted successfully with cascade.\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
