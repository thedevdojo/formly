<?php

namespace Database\Seeders;

use Devdojo\Billing\Models\Plan;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'registered', 'pro', 'business'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $registered = Role::where('name', 'registered')->first();
        $pro = Role::where('name', 'pro')->first();
        $business = Role::where('name', 'business')->first();

        Plan::query()->delete();

        Plan::create([
            'name' => 'Free',
            'description' => 'Everything you need to start collecting responses.',
            'features' => ['Unlimited forms', '100 responses / month', 'All field types', 'CSV export'],
            'monthly_price' => '0',
            'yearly_price' => '0',
            'currency' => '$',
            'active' => true,
            'default' => true,
            'sort_order' => 1,
            'role_id' => $registered->id,
            'limits' => ['responses' => 100],
        ]);

        Plan::create([
            'name' => 'Pro',
            'description' => 'For creators and teams who need more headroom.',
            'features' => ['Everything in Free', '10,000 responses / month', 'Remove Formly branding', 'Custom thank-you pages', 'Email notifications'],
            'monthly_price' => '9',
            'yearly_price' => '90',
            'monthly_price_id' => 'price_pro_monthly',
            'yearly_price_id' => 'price_pro_yearly',
            'currency' => '$',
            'active' => true,
            'sort_order' => 2,
            'role_id' => $pro->id,
            'limits' => ['responses' => 10000],
        ]);

        Plan::create([
            'name' => 'Business',
            'description' => 'For companies running forms at scale.',
            'features' => ['Everything in Pro', 'Unlimited responses', 'Team workspaces', 'Priority support', 'Custom domains'],
            'monthly_price' => '29',
            'yearly_price' => '290',
            'monthly_price_id' => 'price_business_monthly',
            'yearly_price_id' => 'price_business_yearly',
            'currency' => '$',
            'active' => true,
            'sort_order' => 3,
            'role_id' => $business->id,
            'limits' => ['responses' => -1],
        ]);

        Plan::clearCache();
    }
}
