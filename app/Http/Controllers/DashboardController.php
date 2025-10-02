<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $totalCompanies = Company::count();
        $employmentData = $this->getEmploymentStatusData();
        $companyData = $this->getCompanyEmployeeData(); // Fetch company data for pie chart
        $ratioPermanent = $this->calcRatioPermanent();
        $ratioRaces = $this->calcRatioRaces();
        $departmentData = $this->getDepartmentEmployeeData();
        $hiredResignedData = $this->calcHiredResigned();

        // New birthday logic
        $todayBirthdays = $this->getTodayBirthdays();
        $upcomingBirthdays = $this->getUpcomingBirthdays();
        $nextBirthday = $this->getNextBirthday();

        // Fetch job vacancy data directly (including manually entered hired, kiv, and rejected counts)
        $jobs = Job::select('title', 'vacancies', 'applicants', 'interviewed', 'hired')
            ->get();

        return view('dashboard', compact('totalEmployees', 'totalCompanies', 'employmentData', 'companyData', 'ratioPermanent', 'ratioRaces', 'hiredResignedData', 'departmentData', 'jobs', 'todayBirthdays', 'upcomingBirthdays', 'nextBirthday'));
    }

    public function getEmploymentStatusData()
    {
        $year = date('Y'); // Set the current year
        $months = range(1, 12);

        // Initialize monthly data for each category starting with 0
        $monthlyData = [
            'Full Time' => array_fill(1, 12, 0),
            'Contract' => array_fill(1, 12, 0),
            'Protégé' => array_fill(1, 12, 0),
            'Internship' => array_fill(1, 12, 0),
        ];

        // Calculate employees who joined before 2024 and are still active, categorized by employment status
        $initialEmployees = DB::table('employees')
            ->select(DB::raw('employment_status'), DB::raw('COUNT(*) as total'))
            ->whereYear('joined_date', '<', $year)
            ->where(function ($query) use ($year) {
                $query->whereNull('termination_date')
                    ->orWhereYear('termination_date', '>=', $year);
            })
            ->groupBy('employment_status')
            ->get();

        // Set initial counts for each category in January
        foreach ($initialEmployees as $employee) {
            if ($employee->employment_status == 1) {
                $monthlyData['Full Time'][1] += $employee->total;
            } elseif ($employee->employment_status == 2) {
                $monthlyData['Contract'][1] += $employee->total;
            } elseif ($employee->employment_status == 3) {
                $monthlyData['Protégé'][1] += $employee->total;
            } elseif ($employee->employment_status == 4) {
                $monthlyData['Internship'][1] += $employee->total;
            }
        }

        // Get the data of employees who joined in 2024, categorized by employment status
        $joinedEmployees = DB::table('employees')
            ->select(DB::raw('MONTH(joined_date) as month'), DB::raw('employment_status'), DB::raw('COUNT(*) as count'))
            ->whereYear('joined_date', $year)
            ->groupBy(DB::raw('MONTH(joined_date)'), 'employment_status')
            ->get();

        // Get the data of employees who terminated in 2024, categorized by employment status
        $terminatedEmployees = DB::table('employees')
            ->select(DB::raw('MONTH(DATE_ADD(termination_date, INTERVAL 1 MONTH)) as month'), DB::raw('employment_status'), DB::raw('COUNT(*) as count'))
            ->whereYear('termination_date', $year)
            ->groupBy(DB::raw('MONTH(DATE_ADD(termination_date, INTERVAL 1 MONTH))'), 'employment_status')
            ->get();

        // Process the monthly data for joined employees
        foreach ($joinedEmployees as $employee) {
            $month = $employee->month;
            $status = $employee->employment_status;
            if ($status == 1) {
                $monthlyData['Full Time'][$month] += $employee->count;
            } elseif ($status == 2) {
                $monthlyData['Contract'][$month] += $employee->count;
            } elseif ($status == 3) {
                $monthlyData['Protégé'][$month] += $employee->count;
            } elseif ($status == 4) {
                $monthlyData['Internship'][$month] += $employee->count;
            }
        }

        // Process the monthly data for terminated employees
        foreach ($terminatedEmployees as $employee) {
            $month = $employee->month;
            $status = $employee->employment_status;
            if ($status == 1) {
                $monthlyData['Full Time'][$month] -= $employee->count;
            } elseif ($status == 2) {
                $monthlyData['Contract'][$month] -= $employee->count;
            } elseif ($status == 3) {
                $monthlyData['Protégé'][$month] -= $employee->count;
            } elseif ($status == 4) {
                $monthlyData['Internship'][$month] -= $employee->count;
            }
        }

        // Fill in cumulative totals for each category across the months
        for ($i = 2; $i <= 12; $i++) {
            foreach (['Full Time', 'Contract', 'Protégé', 'Internship'] as $category) {
                $monthlyData[$category][$i] += $monthlyData[$category][$i - 1];
            }
        }
        \Log::info('Final Monthly Data:', $monthlyData);
        return $monthlyData;
    }

    public function getCompanyEmployeeData()
    {
        // Fetch the count of employees for each company
        $data = DB::table('employees')
            ->select('company', DB::raw('COUNT(*) as count'))
            ->whereNull('termination_date') // Only count employees without a termination date
            ->groupBy('company')
            ->get();

        // Map company IDs to names
        $companies = Company::pluck('title', 'id')->toArray();

        // Define your desired names for each company ID
        $desiredNames = [
            1 => 'K-Tech', // Map company ID 1 to 'Kridentia Tech'
            2 => 'K-Serve', // Map company ID 2 to 'Kridentia Integrated Services'
            3 => 'K-Inno' // Map company ID 3 to 'Kridentia Innovations'
            // Add more mappings as needed
        ];

        // Prepare data for the chart
        $chartData = [];
        foreach ($data as $item) {
            $companyName = $desiredNames[$item->company] ?? 'Unknown'; // Use desired names
            $chartData[] = [$companyName, $item->count];
        }

        return $chartData;
    }

    public function getDepartmentEmployeeData()
    {
        // Fetch the count of employees for each department
        $data = DB::table('employees')
            ->select('department', DB::raw('COUNT(*) as count'))
            ->whereNull('termination_date') // Only count employees without a termination date
            ->groupBy('department')
            ->get();

        // Map department IDs to names
        $departments = [
            1 => 'Management',
            2 => 'SSO',
            3 => 'Presales',
            4 => 'Software Development',
            5 => 'Sales',
            6 => 'Program Management',
            7 => 'Post Sales',
            8 => 'BIOFIS'
        ];

        // Prepare data for the chart
        $chartDepartment = [];
        foreach ($data as $item) {
            $departmentName = $departments[$item->department] ?? 'Unknown'; // Use department names
            $chartDepartment[] = [$departmentName, $item->count];
        }

        return $chartDepartment;
    }

    public function calcRatioPermanent()
    {
        // Count full-time employees excluding those with a non-null termination_date
        $fullTimeCount = Employee::where('employment_status', '1')
            ->whereNull('termination_date')
            ->count();

        // Count other employees excluding those with a non-null termination_date
        $otherCount = Employee::where('employment_status', '!=', '1')
            ->whereNull('termination_date')
            ->count();

        // Calculate the ratio
        $ratioPermanent = $fullTimeCount . ' : ' . $otherCount;

        return $ratioPermanent;
    }

    public function calcRatioRaces()
    {
        // Count Malay (ethnicity = 1), excluding those with a non-null termination_date
        $malayCount = Employee::where('ethnicity', '1')
            ->whereNull('termination_date')
            ->count();

        // Count Chinese (ethnicity = 2), excluding those with a non-null termination_date
        $chineseCount = Employee::where('ethnicity', '2')
            ->whereNull('termination_date')
            ->count();

        // Count Indian (ethnicity = 3), excluding those with a non-null termination_date
        $indianCount = Employee::where('ethnicity', '3')
            ->whereNull('termination_date')
            ->count();

        // Count Others (ethnicity = 4), excluding those with a non-null termination_date
        $othersCount = Employee::where('ethnicity', '4')
            ->whereNull('termination_date')
            ->count();

        // Construct the ratio string
        $ratioRaces = $malayCount . ' : ' . $chineseCount . ' : ' . $indianCount . ' : ' . $othersCount;

        return $ratioRaces;
    }
    public function calcHiredResigned()
    {
        $currentYear = Carbon::now()->year;

        // Count employees hired this year
        $hiredCount = Employee::whereYear('joined_date', $currentYear)->count();

        // Count employees resigned this year
        $resignedCount = Employee::whereYear('termination_date', $currentYear)->count();

        return [
            'hiredCount' => $hiredCount,
            'resignedCount' => $resignedCount,
        ];
    }

    public function getTodayBirthdays()
    {
        $today = Carbon::today();

        return Employee::select('first_name', 'last_name', 'birthday')
            ->whereNotNull('birthday')
            ->get()
            ->filter(function ($employee) use ($today) {
                $birthday = Carbon::parse($employee->birthday)->year($today->year);

                if ($birthday->format('m-d') === '02-29' && !$birthday->isLeapYear()) {
                    $birthday->day(28);
                }

                return $birthday->isSameDay($today);
            })
            ->map(function ($employee) {
                return [
                    'name' => trim("{$employee->first_name} {$employee->last_name}"),
                    'birthday' => Carbon::parse($employee->birthday)->format('d M'),
                ];
            })
            ->values();
    }

    public function getUpcomingBirthdays($days = 7)
    {
        $today = Carbon::today();
        $end = $today->copy()->addDays($days);

        return Employee::select('first_name', 'last_name', 'birthday')
            ->whereNotNull('birthday')
            ->get()
            ->filter(function ($employee) use ($today, $end) {
                $birthday = Carbon::parse($employee->birthday)->year($today->year);

                if ($birthday->format('m-d') === '02-29' && !$birthday->isLeapYear()) {
                    $birthday->day(28);
                }

                return $birthday->between($today->copy()->addDay(), $end);
            })
            ->map(function ($employee) {
                return [
                    'name' => trim("{$employee->first_name} {$employee->last_name}"),
                    'birthday' => Carbon::parse($employee->birthday)->format('d M'),
                ];
            })
            ->values();
    }

    public function getNextBirthday()
    {
        $today = Carbon::today();
        $end = $today->copy()->addDays(7);

        $employees = Employee::select('first_name', 'last_name', 'birthday')
            ->whereNotNull('birthday')
            ->get();

        // Get birthdays that are NOT within the next 7 days
        $futureBirthdays = $employees->map(function ($employee) use ($today) {
            $bday = Carbon::parse($employee->birthday)->year($today->year);

            // Adjust for past birthdays to next year
            if ($bday->lessThanOrEqualTo($today)) {
                $bday->addYear();
            }

            return [
                'name' => trim("{$employee->first_name} {$employee->last_name}"),
                'birthday' => $bday,
            ];
        })
        ->filter(function ($bday) use ($today, $end) {
            return $bday['birthday']->gt($end); // ONLY include those beyond next 7 days
        })
        ->sortBy('birthday')
        ->first();

        return $futureBirthdays
            ? [
                'name' => $futureBirthdays['name'],
                'birthday' => $futureBirthdays['birthday']->format('d M'),
            ]
            : null;
    }

}
