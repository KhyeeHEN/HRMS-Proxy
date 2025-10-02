<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Company;
use App\Models\CompanyEvent;    // for company events
use App\Models\Holiday;         // for company holidays
use App\Models\Kpi; // ADDED: KPI Model
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Fetch contracts expiring in the next 60 days
        $expiringContracts = $this->getExpiringContracts();

        // Fetch job vacancy data directly (including manually entered hired, kiv, and rejected counts)
        $jobs = Job::select('title', 'vacancies', 'applicants', 'interviewed', 'hired')
            ->get();

        // ADDED: Fetch and combine company events and holidays
        $combinedCalendarData = $this->getCombinedCalendarData();

        return view('dashboard', compact('totalEmployees', 'totalCompanies', 'employmentData', 'companyData', 'ratioPermanent', 'ratioRaces', 'hiredResignedData', 'departmentData', 'jobs', 'todayBirthdays', 'upcomingBirthdays', 'nextBirthday', 'expiringContracts', 'combinedCalendarData'));
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
        Log::info('Final Monthly Data:', $monthlyData);
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

    public function getExpiringContracts($days = 60)
    {
        $today = Carbon::today();
        $expiryThreshold = $today->copy()->addDays($days);

        return Employee::select('first_name', 'last_name', 'expiry_date')
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [$today, $expiryThreshold])
            ->get()
            ->map(function ($employee) {
                return [
                    'name' => trim("{$employee->first_name} {$employee->last_name}"),
                    'expiry_date' => Carbon::parse($employee->expiry_date)->format('d M Y'),
                ];
            })
            ->values();
    }

    // ADDED: New method to fetch and combine company events and holidays
    public function getCombinedCalendarData()
    {
        $today = Carbon::today();
        $oneMonthFromNow = $today->copy()->addMonth();

        // Fetch company events for the next month
        $companyEvents = CompanyEvent::whereBetween('start_date', [$today, $oneMonthFromNow])
            ->get()
            ->map(function ($event) {
                $event->type = 'event';
                return $event;
            });

        // Fetch holidays and automatically adjust to the current year
        $holidays = Holiday::all()
            ->map(function ($holiday) use ($today) {
                $dateh = Carbon::createFromDate($today->year, $holiday->month, $holiday->day);

                // If the holiday has already passed this year, show it for next year
                if ($dateh->lessThan($today)) {
                    $dateh->addYear();
                }

                $holiday->type = 'holiday';
                $holiday->title = $holiday->name;
                $holiday->start_date = $dateh->toDateString();
                return $holiday;
            })
            ->filter(function ($holiday) use ($today, $oneMonthFromNow) {
                // Filter holidays to only show those in the next month
                return Carbon::parse($holiday->start_date)->between($today, $oneMonthFromNow);
            });

        // Combine both collections into a single collection
        $allEvents = $companyEvents->merge($holidays);

        // Sort the combined collection by date
        return $allEvents->sortBy('start_date');
    }

    public function pmsIndex()
    {
        $user = Auth::user();

        // Initialize variables for different access levels
        $kpiCount = 0;
        $appraisalCount = 0;
        $appraisalStatus = collect();
        $staffList = collect();

        if (in_array($user->access, ['Admin', 'HR'])) {
            // Admin and HR can see all KPIs, Appraisals, and Staff
            $kpiCount = Kpi::count();
            $appraisalCount = Appraisal::count();

            $appraisalStatus = Appraisal::with(['staff.job', 'staff.department'])
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();

            $staffList = User::where('access', 'Staff')
                ->with(['job', 'department', 'supervisor'])
                ->orderBy('name', 'asc')
                ->take(10)
                ->get();
        } elseif ($user->access === 'Manager') {
            // Managers can see KPIs, Appraisals, and Staff for their subordinates
            $kpiCount = Kpi::where('manager_id', $user->id)->count();
            $appraisalCount = Appraisal::whereHas('staff', function ($query) use ($user) {
                $query->where('supervisor_id', $user->id);
            })->count();

            $appraisalStatus = Appraisal::whereHas('staff', function ($query) use ($user) {
                $query->where('supervisor_id', $user->id);
            })->with(['staff.job', 'staff.department'])
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();

            $staffList = User::where('supervisor_id', $user->id)
                ->with(['job', 'department', 'supervisor'])
                ->orderBy('name', 'asc')
                ->take(10)
                ->get();
        } else { // Staff
            // Staff members see only their own data
            $kpiCount = Kpi::where('staff_id', $user->id)->count();
            $appraisalCount = Appraisal::where('staff_id', $user->id)->count();

            $appraisalStatus = Appraisal::where('staff_id', $user->id)
                ->with(['staff.job', 'staff.department'])
                ->take(10)
                ->get();

            $staffList = collect([$user])->load(['job', 'department', 'supervisor']);
        }

        return view('pms.dashboard', compact('kpiCount', 'appraisalCount', 'appraisalStatus', 'staffList'));
    }
}
