<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Item;
use App\Traits\ProvidesTrashedCount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use PHPUnit\Framework\Test;
use Tests\TestCase;

class ProvidesTrashedCountTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->artisan('migrate');
  }

  #[Test]
  public function test_trait_shares_trashed_count_for_customer_model()
  {
    // Create some customers and delete some
    Customer::factory(3)->create();
    $trashedCustomers = Customer::factory(2)->create();
    foreach ($trashedCustomers as $customer) {
      $customer->delete();
    }

    // Create a mock controller that uses the trait
    $controller = new class {
      use ProvidesTrashedCount;
      protected $model = Customer::class;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    $controller->testShareTrashedCount();

    // Check if the trashed count is shared with views
    $sharedData = View::getShared();
    $this->assertArrayHasKey('trashedCount', $sharedData);
    $this->assertEquals(2, $sharedData['trashedCount']);
  }

  #[Test]
  public function test_trait_shares_trashed_count_for_item_model()
  {
    // Create some items and delete some
    Item::factory(5)->create();
    $trashedItems = Item::factory(3)->create();
    foreach ($trashedItems as $item) {
      $item->delete();
    }

    // Create a mock controller that uses the trait
    $controller = new class {
      use ProvidesTrashedCount;
      protected $model = Item::class;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    $controller->testShareTrashedCount();

    // Check if the trashed count is shared with views
    $sharedData = View::getShared();
    $this->assertArrayHasKey('trashedCount', $sharedData);
    $this->assertEquals(3, $sharedData['trashedCount']);
  }

  #[Test]
  public function test_trait_shares_zero_when_no_trashed_records()
  {
    // Create only active customers
    Customer::factory(3)->create();

    $controller = new class {
      use ProvidesTrashedCount;
      protected $model = Customer::class;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    $controller->testShareTrashedCount();

    $sharedData = View::getShared();
    $this->assertArrayHasKey('trashedCount', $sharedData);
    $this->assertEquals(0, $sharedData['trashedCount']);
  }

  #[Test]
  public function test_trait_does_nothing_when_no_model_property()
  {
    $controller = new class {
      use ProvidesTrashedCount;
      // No $model property defined

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    $controller->testShareTrashedCount();

    // Should not set trashedCount when no model property is defined
    $sharedData = View::getShared();
    $this->assertArrayNotHasKey('trashedCount', $sharedData);
  }

  #[Test]
  public function test_trait_does_nothing_when_model_property_is_null()
  {
    $controller = new class {
      use ProvidesTrashedCount;
      protected $model = null;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    $controller->testShareTrashedCount();

    // Should not set trashedCount when model property is null
    $sharedData = View::getShared();
    $this->assertArrayNotHasKey('trashedCount', $sharedData);
  }

  #[Test]
  public function test_trait_method_is_protected()
  {
    $controller = new class {
      use ProvidesTrashedCount;
      protected $model = Customer::class;
    };

    $reflection = new \ReflectionClass($controller);
    $method = $reflection->getMethod('shareTrashedCount');

    $this->assertTrue($method->isProtected());
  }

  #[Test]
  public function test_trait_updates_count_when_records_change()
  {
    Customer::factory(2)->create();

    $controller = new class {
      use ProvidesTrashedCount;
      protected $model = Customer::class;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    // Initially no trashed records
    $controller->testShareTrashedCount();
    $sharedData = View::getShared();
    $this->assertEquals(0, $sharedData['trashedCount']);

    // Delete a customer
    Customer::first()->delete();

    // Call again to update count
    $controller->testShareTrashedCount();
    $sharedData = View::getShared();
    $this->assertEquals(1, $sharedData['trashedCount']);

    // Delete another customer
    Customer::where('deleted_at', null)->first()->delete();

    // Call again to update count
    $controller->testShareTrashedCount();
    $sharedData = View::getShared();
    $this->assertEquals(2, $sharedData['trashedCount']);
  }

  #[Test]
  public function test_trait_works_with_different_model_classes()
  {
    // Test with Customer model
    Customer::factory()->create()->delete();

    $customerController = new class {
      use ProvidesTrashedCount;
      protected $model = Customer::class;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    $customerController->testShareTrashedCount();
    $sharedData = View::getShared();
    $this->assertEquals(1, $sharedData['trashedCount']);

    // Test with Item model (should override the previous value)
    Item::factory(3)->create();
    foreach (Item::all() as $item) {
      $item->delete();
    }

    $itemController = new class {
      use ProvidesTrashedCount;
      protected $model = Item::class;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    $itemController->testShareTrashedCount();
    $sharedData = View::getShared();
    $this->assertEquals(3, $sharedData['trashedCount']);
  }

  #[Test]
  public function test_trait_correctly_counts_only_trashed_records()
  {
    // Create customers - some active, some trashed
    $activeCustomer = Customer::factory()->create();
    $trashedCustomer1 = Customer::factory()->create();
    $trashedCustomer2 = Customer::factory()->create();

    $trashedCustomer1->delete();
    $trashedCustomer2->delete();

    $controller = new class {
      use ProvidesTrashedCount;
      protected $model = Customer::class;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    $controller->testShareTrashedCount();

    $sharedData = View::getShared();
    $this->assertEquals(2, $sharedData['trashedCount']);

    // Verify we have 1 active and 2 trashed
    $this->assertEquals(1, Customer::count());
    $this->assertEquals(2, Customer::onlyTrashed()->count());
  }

  #[Test]
  public function test_trait_view_share_persists_across_multiple_calls()
  {
    Customer::factory()->create()->delete();

    $controller = new class {
      use ProvidesTrashedCount;
      protected $model = Customer::class;

      public function testShareTrashedCount()
      {
        $this->shareTrashedCount();
      }
    };

    // Call multiple times
    $controller->testShareTrashedCount();
    $controller->testShareTrashedCount();
    $controller->testShareTrashedCount();

    // Should still have the correct count
    $sharedData = View::getShared();
    $this->assertEquals(1, $sharedData['trashedCount']);
  }
}
