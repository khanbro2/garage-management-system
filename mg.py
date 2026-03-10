
# Create Database Migrations

migrations = {
    "2014_10_12_000000_create_garages_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'suspended', 'pending'])->default('pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garages');
    }
};
""",

    "2014_10_12_100000_create_subscription_plans_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_yearly', 10, 2);
            $table->integer('max_vehicles')->nullable(); // null = unlimited
            $table->integer('max_staff')->nullable();
            $table->boolean('sms_reminders')->default(false);
            $table->boolean('api_access')->default(false);
            $table->boolean('advanced_reporting')->default(false);
            $table->boolean('multiple_locations')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
""",

    "2014_10_12_200000_create_garage_subscriptions_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garage_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garage_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained();
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamp('cancelled_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garage_subscriptions');
    }
};
""",

    "2014_10_12_300000_create_users_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garage_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['super_admin', 'garage_owner', 'garage_staff'])->default('garage_staff');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
""",

    "2024_01_01_000001_create_customers_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garage_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['garage_id', 'phone']);
            $table->index(['garage_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
""",

    "2024_01_01_000002_create_vehicles_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garage_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('registration_number')->index();
            $table->string('make');
            $table->string('model');
            $table->year('year')->nullable();
            $table->string('vin')->nullable();
            $table->string('color')->nullable();
            $table->integer('mileage')->nullable();
            $table->date('mot_expiry')->nullable();
            $table->date('service_due')->nullable();
            $table->date('last_mot_check')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['garage_id', 'registration_number']);
            $table->index(['garage_id', 'mot_expiry']);
            $table->index(['garage_id', 'service_due']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
""",

    "2024_01_01_000003_create_mot_records_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mot_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->date('test_date');
            $table->enum('result', ['pass', 'fail']);
            $table->integer('mileage')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('defects')->nullable(); // Store MOT defects as JSON
            $table->string('mot_test_number')->nullable(); // External MOT test reference
            $table->timestamps();
            
            $table->index(['vehicle_id', 'test_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mot_records');
    }
};
""",

    "2024_01_01_000004_create_service_records_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Technician who performed service
            $table->date('service_date');
            $table->enum('service_type', ['interim', 'full', 'major', 'repair', 'diagnostic']);
            $table->text('description');
            $table->string('technician');
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('mileage')->nullable();
            $table->text('notes')->nullable();
            $table->json('parts_used')->nullable();
            $table->date('next_service_due')->nullable();
            $table->timestamps();
            
            $table->index(['vehicle_id', 'service_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
};
""",

    "2024_01_01_000005_create_reminders_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garage_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['mot_expiry', 'service_due']);
            $table->date('due_date');
            $table->enum('status', ['pending', 'sent', 'failed', 'cancelled'])->default('pending');
            $table->enum('notification_method', ['email', 'sms', 'both'])->default('email');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->integer('days_before')->default(30); // How many days before expiry
            $table->timestamps();
            
            $table->index(['garage_id', 'status', 'due_date']);
            $table->index(['vehicle_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
""",

    "2024_01_01_000006_create_activity_logs_table.php": """
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garage_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('action'); // created, updated, deleted, viewed
            $table->string('entity_type'); // customer, vehicle, service
            $table->unsignedBigInteger('entity_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['garage_id', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
"""
}

# Save migrations
import os
os.makedirs('/mnt/kimi/output/database/migrations', exist_ok=True)

for filename, content in migrations.items():
    with open(f'/mnt/kimi/output/database/migrations/{filename}', 'w') as f:
        f.write(content)

print("✅ Database migrations created successfully")
print(f"Total migrations: {len(migrations)}")
