<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\GeneralFactor;
use App\Models\Factor;
use App\Models\SubFactor;
use App\Models\RatingScale;
use App\Models\RatingScaleItem;
use App\Models\Evaluation;
use App\Models\Employee;
use App\Models\User;
use App\Models\EmployeeEvaluation;
use App\Models\EmployeeEvaluationDetail;

class PerformanceSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // 1) General Factors
            $generalFactorsData = [
                ['factor_name' => 'Time Management', 'description' => 'Ability to effectively manage work time and meet deadlines.'],
                ['factor_name' => 'Communication Skills', 'description' => 'Ability to communicate clearly and effectively in both written and verbal forms.'],
                ['factor_name' => 'Teamwork', 'description' => 'Ability to collaborate and contribute to team objectives.'],
                ['factor_name' => 'Work Quality', 'description' => 'Accuracy, consistency, and completeness of work performed.'],
                ['factor_name' => 'Initiative and Innovation', 'description' => 'Proactiveness and creativity in solving problems or improving processes.'],
            ];

            $gf = [];
            foreach ($generalFactorsData as $data) {
                $gf[$data['factor_name']] = GeneralFactor::firstOrCreate(
                    ['factor_name' => $data['factor_name']],
                    ['description' => $data['description']]
                );
            }

            // 2) Factors
            $factorsData = [
                ['general' => 'Time Management', 'factor_name' => 'Punctuality', 'description' => 'Arrives on time for work and meetings.'],
                ['general' => 'Time Management', 'factor_name' => 'Task Prioritization', 'description' => 'Organizes tasks based on urgency and importance.'],
                ['general' => 'Time Management', 'factor_name' => 'Meeting Deadlines', 'description' => 'Completes assigned work within required timelines.'],
                ['general' => 'Communication Skills', 'factor_name' => 'Written Communication', 'description' => 'Produces clear, error-free written materials.'],
                ['general' => 'Communication Skills', 'factor_name' => 'Verbal Communication', 'description' => 'Speaks clearly and listens actively.'],
                ['general' => 'Teamwork', 'factor_name' => 'Collaboration', 'description' => 'Works cooperatively and supports teammates.'],
                ['general' => 'Teamwork', 'factor_name' => 'Conflict Resolution', 'description' => 'Manages disagreements constructively.'],
                ['general' => 'Work Quality', 'factor_name' => 'Attention to Detail', 'description' => 'Ensures accuracy and completeness in deliverables.'],
                ['general' => 'Work Quality', 'factor_name' => 'Consistency', 'description' => 'Produces steady, reliable results.'],
                ['general' => 'Initiative and Innovation', 'factor_name' => 'Problem Solving', 'description' => 'Finds effective solutions with minimal supervision.'],
                ['general' => 'Initiative and Innovation', 'factor_name' => 'Creativity', 'description' => 'Suggests new ideas or improvements.'],
            ];

            $factors = [];
            foreach ($factorsData as $data) {
                $general = $gf[$data['general']];
                $factors[$data['factor_name']] = Factor::firstOrCreate(
                    ['general_factor_id' => $general->id, 'factor_name' => $data['factor_name']],
                    ['description' => $data['description']]
                );
            }

            // 3) Sub Factors
            $subFactorsData = [
                ['general' => 'Time Management', 'factor' => 'Punctuality', 'sub_factor_name' => 'Arrives before official hours', 'description' => 'Consistently reports before start time.'],
                ['general' => 'Time Management', 'factor' => 'Punctuality', 'sub_factor_name' => 'Rarely late for meetings', 'description' => 'Demonstrates strong time discipline.'],
                ['general' => 'Time Management', 'factor' => 'Task Prioritization', 'sub_factor_name' => 'Uses daily task lists', 'description' => 'Plans and tracks work daily.'],
                ['general' => 'Time Management', 'factor' => 'Meeting Deadlines', 'sub_factor_name' => 'Submits reports on time', 'description' => 'Avoids deadline extensions.'],
                ['general' => 'Communication Skills', 'factor' => 'Written Communication', 'sub_factor_name' => 'Writes clear and concise emails', 'description' => 'Uses proper tone and grammar.'],
                ['general' => 'Communication Skills', 'factor' => 'Verbal Communication', 'sub_factor_name' => 'Listens attentively', 'description' => 'Pays attention to feedback.'],
                ['general' => 'Teamwork', 'factor' => 'Collaboration', 'sub_factor_name' => 'Offers help to team members', 'description' => 'Supports teammates when needed.'],
                ['general' => 'Teamwork', 'factor' => 'Conflict Resolution', 'sub_factor_name' => 'Addresses issues calmly', 'description' => 'Handles disagreements maturely.'],
                ['general' => 'Work Quality', 'factor' => 'Attention to Detail', 'sub_factor_name' => 'Produces error-free work', 'description' => 'Double-checks before submission.'],
                ['general' => 'Initiative and Innovation', 'factor' => 'Problem Solving', 'sub_factor_name' => 'Suggests practical solutions', 'description' => 'Offers ideas to fix issues quickly.'],
            ];

            $subFactors = [];
            foreach ($subFactorsData as $data) {
                $factor = $factors[$data['factor']];
                $subFactors[$data['sub_factor_name']] = SubFactor::firstOrCreate(
                    ['factor_id' => $factor->id, 'sub_factor_name' => $data['sub_factor_name']],
                    ['description' => $data['description']]
                );
            }

            // 4) Rating Scale and items
            $scale = RatingScale::firstOrCreate(
                ['scale_name' => 'Standard 5-Point Scale'],
                ['description' => 'Standard performance rating scale.']
            );

            $itemsData = [
                ['item_name' => 'Excellent', 'score' => 5, 'description' => 'Exceeds expectations in all areas.'],
                ['item_name' => 'Very Good', 'score' => 4, 'description' => 'Consistently performs above expectations.'],
                ['item_name' => 'Good', 'score' => 3, 'description' => 'Meets all performance expectations.'],
                ['item_name' => 'Fair', 'score' => 2, 'description' => 'Needs improvement in some areas.'],
                ['item_name' => 'Poor', 'score' => 1, 'description' => 'Performance below expectations.'],
            ];

            $ratingItems = [];
            foreach ($itemsData as $data) {
                $ratingItems[$data['item_name']] = RatingScaleItem::firstOrCreate(
                    ['rating_scale_id' => $scale->id, 'item_name' => $data['item_name']],
                    ['score' => $data['score'], 'description' => $data['description']]
                );
            }

            // 5) Evaluations (3 examples)
            $evaluations = [];
            $evalsData = [
                [
                    'evaluation_name' => '2025 Mid-Year Review — Finance',
                    'description' => 'Finance staff mid-year review.',
                    'start_date' => '2025-01-01',
                    'end_date' => '2025-06-30',
                    'status' => 'active',
                ],
                [
                    'evaluation_name' => '2025 Mid-Year Review — IT',
                    'description' => 'IT staff mid-year review.',
                    'start_date' => '2025-01-01',
                    'end_date' => '2025-06-30',
                    'status' => 'active',
                ],
                [
                    'evaluation_name' => '2025 Mid-Year Review — HR',
                    'description' => 'HR staff mid-year review.',
                    'start_date' => '2025-01-01',
                    'end_date' => '2025-06-30',
                    'status' => 'active',
                ],
            ];

            foreach ($evalsData as $data) {
                $evaluations[$data['evaluation_name']] = Evaluation::firstOrCreate(
                    ['evaluation_name' => $data['evaluation_name']],
                    [
                        'description' => $data['description'],
                        'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                        'status' => $data['status'],
                    ]
                );
            }

            // 6) Sample Employee Evaluation (one header with 3 lines)
            $employee = Employee::query()->first();
            $evaluator = User::query()->first();
            $financeEval = $evaluations['2025 Mid-Year Review — Finance'] ?? null;

            if ($employee && $evaluator && $financeEval) {
                $header = EmployeeEvaluation::firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'evaluation_id' => $financeEval->id,
                        'evaluator_id' => $evaluator->id,
                        'evaluation_date' => '2025-06-30',
                    ],
                    [
                        'total_score' => null,
                        'comments' => 'Annual mid-year evaluation.',
                        'status' => 'completed',
                    ]
                );

                $lines = [
                    ['sub' => 'Arrives before official hours', 'item' => 'Very Good'], // 4
                    ['sub' => 'Rarely late for meetings', 'item' => 'Excellent'],      // 5
                    ['sub' => 'Submits reports on time', 'item' => 'Good'],           // 3
                ];

                $scores = [];
                foreach ($lines as $line) {
                    $sub = $subFactors[$line['sub']] ?? null;
                    $item = $ratingItems[$line['item']] ?? null;
                    if ($sub && $item) {
                        EmployeeEvaluationDetail::firstOrCreate(
                            [
                                'employee_evaluation_id' => $header->id,
                                'sub_factor_id' => $sub->id,
                            ],
                            [
                                'rating_scale_item_id' => $item->id,
                                'comments' => null,
                            ]
                        );
                        $scores[] = (int) $item->score;
                    }
                }

                if (count($scores) > 0) {
                    $avg = round(array_sum($scores) / count($scores), 2);
                    $header->total_score = $avg;
                    $header->save();
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PerformanceSeeder error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
}
