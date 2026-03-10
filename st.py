
import os

# Create the complete Laravel project structure
project_structure = """
garage-management-saas/
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── SendReminders.php
│   │   └── Kernel.php
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── ForgotPasswordController.php
│   │   │   ├── Api/
│   │   │   │   ├── CustomerController.php
│   │   │   │   ├── VehicleController.php
│   │   │   │   ├── MotController.php
│   │   │   │   └── ServiceController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── VehicleController.php
│   │   │   ├── MotController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── ReminderController.php
│   │   │   ├── StaffController.php
│   │   │   ├── SubscriptionController.php
│   │   │   └── SuperAdmin/
│   │   │       ├── DashboardController.php
│   │   │       ├── GarageController.php
│   │   │       └── SubscriptionPlanController.php
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   ├── CheckGarageSubscription.php
│   │   │   ├── EnsureGarageAccess.php
│   │   │   ├── RedirectIfAuthenticated.php
│   │   │   ├── RoleMiddleware.php
│   │   │   └── TrimStrings.php
│   │   ├── Requests/
│   │   │   ├── StoreCustomerRequest.php
│   │   │   ├── StoreVehicleRequest.php
│   │   │   ├── StoreServiceRequest.php
│   │   │   └── StoreStaffRequest.php
│   │   └── Kernel.php
│   ├── Jobs/
│   │   ├── SendMotReminderEmail.php
│   │   └── SendServiceReminderEmail.php
│   ├── Models/
│   │   ├── Garage.php
│   │   ├── User.php
│   │   ├── Customer.php
│   │   ├── Vehicle.php
│   │   ├── MotRecord.php
│   │   ├── ServiceRecord.php
│   │   ├── SubscriptionPlan.php
│   │   └── GarageSubscription.php
│   ├── Policies/
│   │   ├── CustomerPolicy.php
│   │   ├── VehiclePolicy.php
│   │   └── ServiceRecordPolicy.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   ├── RouteServiceProvider.php
│   │   └── TenancyServiceProvider.php
│   ├── Services/
│   │   ├── MotApiService.php
│   │   ├── SubscriptionService.php
│   │   ├── ReminderService.php
│   │   └── TenantManager.php
│   └── Traits/
│       └── BelongsToGarage.php
├── bootstrap/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── queue.php
│   ├── services.php
│   └── tenancy.php
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── SubscriptionPlanSeeder.php
│       └── SuperAdminSeeder.php
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── customers/
│   │   ├── vehicles/
│   │   ├── services/
│   │   ├── reminders/
│   │   ├── staff/
│   │   ├── super-admin/
│   │   └── layouts/
│   ├── js/
│   ├── css/
│   └── sass/
├── routes/
│   ├── api.php
│   ├── web.php
│   └── console.php
├── storage/
├── tests/
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
└── README.md
"""

print(project_structure)
print("\n✅ Project structure defined")
