<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // User & Role Management
            'create_users',
            'view_users',
            'update_users',
            'delete_users',
            'assign_roles',
            'revoke_roles',
            'reset_passwords',

            // Employee Management
            'create_employee_profile',
            'view_employee_profile',
            'view_own_profile',
            'update_employee_profile',
            'update_own_profile',
            'delete_employee_profile',
            'manage_employee_documents',
            'view_employee_documents',
            'view_own_documents',
            'manage_emergency_contacts',
            'terminate_employee',

            // Recruitment & Onboarding
            'create_job_posting',
            'view_job_posting',
            'update_job_posting',
            'delete_job_posting',
            'manage_applications',
            'view_applications',
            'shortlist_candidates',
            'schedule_interviews',
            'update_interview_results',
            'hire_candidate',
            'onboard_employee',

            // Attendance & Leave
            'view_attendance_records',
            'view_own_attendance',
            'log_attendance_manual',
            'update_attendance',
            'delete_attendance',
            'request_leave',
            'approve_leave',
            'approve_team_leave',
            'reject_leave',
            'cancel_leave',
            'view_leave_balance',
            'view_own_leave_balance',
            'view_team_leave',

            // Performance Management
            'create_performance_review_cycle',
            'view_performance_reviews',
            'view_own_performance_reviews',
            'view_team_performance_reviews',
            'update_performance_reviews',
            'delete_performance_reviews',
            'submit_self_assessment',
            'give_peer_review',
            'approve_manager_review',
            'set_goals_objectives',
            'set_team_goals',

            // Payroll & Compensation
            'generate_payroll',
            'view_payroll',
            'view_payroll_readonly',
            'update_payroll',
            'delete_payroll',
            'manage_salary_structure',
            'manage_allowances_benefits',
            'manage_deductions',
            'approve_payroll',
            'view_own_payslip',
            'view_team_payslips',
            'download_payslip',

            // Training & Development
            'create_training_program',
            'view_training_program',
            'update_training_program',
            'delete_training_program',
            'enroll_employee_training',
            'enroll_self_training',
            'track_training_progress',
            'issue_certificates',

            // Documents & Policies
            'upload_hr_policy',
            'update_hr_policy',
            'delete_hr_policy',
            'view_hr_policy',
            'upload_employee_document',
            'view_employee_document',
            'delete_employee_document',

            // Reports & Analytics
            'generate_hr_reports',
            'export_hr_reports',
            'view_dashboards',
            'view_audit_logs',

            // System & Settings
            'manage_system_settings',
            'manage_notification_templates',
            'manage_integrations',
            'manage_backups',
            'view_system_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and assign permissions
        $roles = [
            'Super Admin' => [
                // Full system access - all permissions
                $permissions
            ],

            'HR Manager' => [
                // Oversees all HR functions
                'view_users', 'update_users', 'reset_passwords',
                'create_employee_profile', 'view_employee_profile', 'update_employee_profile', 'delete_employee_profile',
                'manage_employee_documents', 'view_employee_documents', 'manage_emergency_contacts', 'terminate_employee',
                'create_job_posting', 'view_job_posting', 'update_job_posting', 'delete_job_posting',
                'manage_applications', 'view_applications', 'shortlist_candidates', 'schedule_interviews',
                'update_interview_results', 'hire_candidate', 'onboard_employee',
                'view_attendance_records', 'log_attendance_manual', 'update_attendance', 'delete_attendance',
                'approve_leave', 'reject_leave', 'cancel_leave', 'view_leave_balance',
                'create_performance_review_cycle', 'view_performance_reviews', 'update_performance_reviews',
                'delete_performance_reviews', 'set_goals_objectives',
                'view_payroll', 'manage_salary_structure', 'manage_allowances_benefits', 'manage_deductions',
                'create_training_program', 'view_training_program', 'update_training_program', 'delete_training_program',
                'enroll_employee_training', 'track_training_progress', 'issue_certificates',
                'upload_hr_policy', 'update_hr_policy', 'delete_hr_policy', 'view_hr_policy',
                'upload_employee_document', 'view_employee_document', 'delete_employee_document',
                'generate_hr_reports', 'export_hr_reports', 'view_dashboards', 'view_audit_logs',
                'manage_notification_templates'
            ],

            'HR Officer' => [
                // Handles employee records, leaves, recruitment
                'view_users',
                'create_employee_profile', 'view_employee_profile', 'update_employee_profile',
                'manage_employee_documents', 'view_employee_documents', 'manage_emergency_contacts',
                'view_job_posting', 'manage_applications', 'view_applications', 'schedule_interviews',
                'update_interview_results', 'onboard_employee',
                'view_attendance_records', 'log_attendance_manual', 'update_attendance',
                'approve_leave', 'reject_leave', 'view_leave_balance',
                'view_performance_reviews', 'update_performance_reviews',
                'view_payroll', 'manage_allowances_benefits',
                'view_training_program', 'enroll_employee_training', 'track_training_progress',
                'view_hr_policy', 'upload_employee_document', 'view_employee_document',
                'generate_hr_reports', 'view_dashboards'
            ],

            'Recruiter' => [
                // Focuses only on hiring
                'create_job_posting', 'view_job_posting', 'update_job_posting', 'delete_job_posting',
                'manage_applications', 'view_applications', 'shortlist_candidates', 'schedule_interviews',
                'update_interview_results', 'hire_candidate', 'onboard_employee',
                'view_employee_profile', 'create_employee_profile',
                'generate_hr_reports', 'view_dashboards'
            ],

            'Payroll Manager' => [
                // Manages salaries, deductions, allowances
                'generate_payroll', 'view_payroll', 'update_payroll', 'delete_payroll',
                'manage_salary_structure', 'manage_allowances_benefits', 'manage_deductions',
                'approve_payroll', 'view_team_payslips',
                'view_employee_profile', 'view_attendance_records',
                'generate_hr_reports', 'export_hr_reports', 'view_dashboards'
            ],

            'Finance Officer' => [
                // Read-only payroll access, manage budgets
                'view_payroll_readonly', 'view_team_payslips',
                'view_employee_profile', 'view_attendance_records',
                'generate_hr_reports', 'export_hr_reports', 'view_dashboards'
            ],

            'Department Manager' => [
                // Approves leave, evaluations for their team
                'view_team_leave', 'approve_team_leave', 'view_leave_balance',
                'view_team_performance_reviews', 'update_performance_reviews', 'set_team_goals',
                'view_own_attendance', 'view_attendance_records',
                'view_employee_profile', 'update_employee_profile',
                'view_team_payslips',
                'enroll_employee_training', 'track_training_progress',
                'view_hr_policy', 'view_employee_document',
                'generate_hr_reports', 'view_dashboards',
                // Employee permissions
                'view_own_profile', 'update_own_profile', 'view_own_documents',
                'request_leave', 'view_own_leave_balance', 'submit_self_assessment',
                'give_peer_review', 'view_own_performance_reviews', 'view_own_payslip',
                'download_payslip', 'enroll_self_training'
            ],

            'Team Lead' => [
                // Similar to manager but smaller scope
                'approve_team_leave', 'view_team_leave', 'view_leave_balance',
                'view_team_performance_reviews', 'set_team_goals',
                'view_own_attendance', 'view_attendance_records',
                'view_employee_profile',
                'view_hr_policy', 'view_employee_document',
                // Employee permissions
                'view_own_profile', 'update_own_profile', 'view_own_documents',
                'request_leave', 'view_own_leave_balance', 'submit_self_assessment',
                'give_peer_review', 'view_own_performance_reviews', 'view_own_payslip',
                'download_payslip', 'enroll_self_training'
            ],

            'Employee' => [
                // Limited to their own profile, requests, payslips
                'view_own_profile', 'update_own_profile', 'view_own_documents',
                'view_own_attendance', 'request_leave', 'view_own_leave_balance',
                'submit_self_assessment', 'give_peer_review', 'view_own_performance_reviews',
                'view_own_payslip', 'download_payslip',
                'view_training_program', 'enroll_self_training',
                'view_hr_policy'
            ],

            'Intern' => [
                // Minimal system access
                'view_own_profile', 'update_own_profile', 'view_own_documents',
                'view_own_attendance', 'request_leave', 'view_own_leave_balance',
                'view_training_program', 'enroll_self_training',
                'view_hr_policy'
            ],

            'System Auditor' => [
                // Read-only access for compliance
                'view_users', 'view_employee_profile', 'view_employee_documents',
                'view_job_posting', 'view_applications',
                'view_attendance_records', 'view_leave_balance',
                'view_performance_reviews', 'view_payroll_readonly',
                'view_training_program', 'view_hr_policy', 'view_employee_document',
                'generate_hr_reports', 'export_hr_reports', 'view_dashboards',
                'view_audit_logs', 'view_system_logs'
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            
            // Flatten permissions array for Super Admin (since it contains nested array)
            if ($roleName === 'Super Admin') {
                $rolePermissions = $rolePermissions[0];
            }
            
            $role->givePermissionTo($rolePermissions);
        }

        $this->command->info('Roles and permissions created successfully!');
    }
}