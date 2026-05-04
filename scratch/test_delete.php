<?php
use App\Models\Customer;

$c = Customer::create(['name' => 'Delete Test']);
echo "Created customer ID: " . $c->id . "\n";

try {
    $c->delete();
    echo "Deleted successfully.\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
