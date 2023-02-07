<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\CompanyBranch;
use Illuminate\Support\Str;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function created(Company $company)
    {
        $branch =new CompanyBranch();
        $branch->uuid = Str::uuid();
        $branch->company_id = $company->id;
        $branch->user_created_by = $company->user_created_by;
        $branch->country_id = $company->country_id;
        $branch->city_id = $company->city_id;
        $branch->branch_name = $company->name . '(head office)';
        $branch->email = $company->email;
        $branch->phone_no = $company->phone_no;
        $branch->address = $company->address;
        $branch->latitude = $company->latitude;
        $branch->longitude = $company->longitude;
        $branch->status = 'active';
        $branch->is_main_branch = '1';
        $branch->save();
    }

    /**
     * Handle the Company "updated" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function updated(Company $company)
    {
        //
    }

    /**
     * Handle the Company "deleted" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function deleted(Company $company)
    {
        //
    }

    /**
     * Handle the Company "restored" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function restored(Company $company)
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     *
     * @param  \App\Models\Company  $company
     * @return void
     */
    public function forceDeleted(Company $company)
    {
        //
    }
}
