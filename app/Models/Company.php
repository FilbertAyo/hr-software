<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'company_short_name',
        'contact_person',
        'city',
        'country',
        'address',
        'phone_no',
        'fax_no',
        'email',
        'website',
        'tin_no',
        'vat_no',
        'district_no',
        'nssf_control_number',
        'wcf_registration_number',
        'start_month',
        'start_year',
        'leave_accumulation_per_month',
        'alias_company_name',
        'weekday_overtime_rate',
        'saturday_overtime_rate',
        'weekend_holiday_overtime_rate',
        'wcf_rate',
        'sdl_rate',
        'advance_rate',
        'sdl_exempt',
        'ot_included_wcf',
        'leave_sold_included_wcf',
        'max_leave_accumulated_days',
        'omit_sundays_leave',
        'omit_holidays_leave',
        'hod_approval_required',
        'approve_employee',
        'bypass_advance_limit',
        'leave_approve',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id');
    }

    /**
     * Check if company is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
