<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $staff_id
 * @property int $manager_id
 * @property string $appraisal_year
 * @property string|null $staff_achievements
 * @property string|null $manager_review
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kpi> $kpis
 * @property-read int|null $kpis_count
 * @property-read \App\Models\User $manager
 * @property-read \App\Models\User $staff
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal whereAppraisalYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal whereManagerReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal whereStaffAchievements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appraisal whereUpdatedAt($value)
 */
	class Appraisal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $asset_id
 * @property string|null $asset_name
 * @property int|null $department
 * @property int|null $type
 * @property string|null $status
 * @property string|null $model
 * @property string|null $sn_no
 * @property string|null $cpu
 * @property string|null $ram
 * @property string|null $hdd
 * @property string|null $hdd_bal
 * @property string|null $hdd2
 * @property string|null $hdd2_bal
 * @property string|null $ssd
 * @property string|null $ssd_bal
 * @property string|null $os
 * @property string|null $os_key
 * @property string|null $office
 * @property string|null $office_key
 * @property string|null $office_login
 * @property string|null $antivirus
 * @property string|null $synology
 * @property string|null $dop
 * @property string|null $warranty_end
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AssetCategory|null $category
 * @property-read \App\Models\AssetAssignment|null $currentAssignment
 * @property-read \App\Models\JobTitle|null $departmentInfo
 * @property-read \App\Models\Employee|null $employee
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereAntivirus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereAssetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCpu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereDop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereHdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereHdd2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereHdd2Bal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereHddBal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereOffice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereOfficeKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereOfficeLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereOsKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereRam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSnNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSsd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSsdBal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereSynology($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asset whereWarrantyEnd($value)
 */
	class Asset extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $asset_id
 * @property int|null $employee_id
 * @property string $assigned_at
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset $asset
 * @property-read \App\Models\Employee|null $employee
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignment whereUpdatedAt($value)
 */
	class AssetAssignment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $asset_id
 * @property int|null $employee_id
 * @property string $assigned_at
 * @property string|null $returned_at
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset $asset
 * @property-read \App\Models\Employee|null $employee
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetAssignmentHistory whereUpdatedAt($value)
 */
	class AssetAssignmentHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asset> $assets
 * @property-read int|null $assets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssetCategory whereUpdatedAt($value)
 */
	class AssetCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $address
 * @property string|null $type
 * @property string $country
 * @property int|null $parent
 * @property string $timezone
 * @property string|null $heads
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereHeads($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereType($value)
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyEvent whereUpdatedAt($value)
 */
	class CompanyEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string|null $namecap
 * @property string $name
 * @property string|null $iso3
 * @property int|null $numcode
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereIso3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereNamecap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereNumcode($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Department whereName($value)
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|E_Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|E_Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|E_Status query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|E_Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|E_Status whereName($value)
 */
	class E_Status extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $employee_id
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property int|null $nationality
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $gender
 * @property string|null $marital_status
 * @property string|null $ssn_num
 * @property string|null $nic_num
 * @property string|null $other_id
 * @property string|null $driving_license
 * @property string|null $driving_license_exp_date
 * @property int|null $employment_status
 * @property string|null $job_title
 * @property string|null $pay_grade
 * @property string|null $work_station_id
 * @property string|null $branch
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $country
 * @property int|null $state
 * @property string|null $postal_code
 * @property string|null $home_phone
 * @property string|null $mobile_phone
 * @property string|null $work_phone
 * @property string|null $work_email
 * @property string|null $private_email
 * @property \Illuminate\Support\Carbon|null $joined_date
 * @property string|null $confirmation_date
 * @property string|null $supervisor
 * @property string|null $indirect_supervisors
 * @property int|null $company
 * @property int|null $department
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property string|null $custom2
 * @property string|null $custom3
 * @property string|null $custom4
 * @property string|null $custom5
 * @property string|null $custom6
 * @property string|null $custom7
 * @property string|null $custom8
 * @property string|null $custom9
 * @property string|null $custom10
 * @property \Illuminate\Support\Carbon|null $termination_date
 * @property string|null $notes
 * @property string|null $status
 * @property int|null $ethnicity
 * @property int|null $immigration_status
 * @property string|null $epf_no
 * @property string|null $socso
 * @property string|null $lhdn_no
 * @property int|null $family
 * @property string|null $qualification
 * @property string|null $experience
 * @property string|null $photo
 * @property string|null $folder
 * @property-read \App\Models\Company|null $companyStructure
 * @property-read \App\Models\Country|null $countryName
 * @property-read \App\Models\Department|null $departmentName
 * @property-read \App\Models\E_Status|null $employmentStatus
 * @property-read \App\Models\Ethnicity|null $ethnicityName
 * @property-read \App\Models\Family|null $familyDetails
 * @property-read \App\Models\JobTitle|null $jobTitle
 * @property-read \App\Models\Nationality|null $national
 * @property-read \App\Models\PayGrade|null $payGrade
 * @property-read \App\Models\State|null $stateName
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereConfirmationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom6($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom7($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom8($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCustom9($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDrivingLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDrivingLicenseExpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmploymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEpfNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEthnicity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereFolder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHomePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereImmigrationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereIndirectSupervisors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJoinedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereLhdnNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereMobilePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNicNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereOtherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePayGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePrivateEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereQualification($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSocso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSsnNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereSupervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTerminationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereWorkEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereWorkPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereWorkStationId($value)
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ethnicity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ethnicity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ethnicity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ethnicity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ethnicity whereName($value)
 */
	class Ethnicity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $ssn_num
 * @property string|null $spouse_name
 * @property string|null $spouse_status
 * @property string|null $spouse_ic
 * @property string|null $spouse_tax
 * @property string|null $noc_under
 * @property string|null $tax_under
 * @property string|null $noc_above
 * @property string|null $tax_above
 * @property string|null $child1
 * @property string|null $child2
 * @property string|null $child3
 * @property string|null $child4
 * @property string|null $child5
 * @property string|null $child6
 * @property string|null $child7
 * @property string|null $child8
 * @property string|null $child9
 * @property string|null $child10
 * @property string|null $contact1_name
 * @property string|null $contact1_no
 * @property string|null $contact1_rel
 * @property string|null $contact1_add
 * @property string|null $contact2_name
 * @property string|null $contact2_no
 * @property string|null $contact2_rel
 * @property string|null $contact2_add
 * @property string|null $contact3_name
 * @property string|null $contact3_no
 * @property string|null $contact3_rel
 * @property string|null $contact3_add
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild10($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild6($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild7($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild8($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereChild9($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact1Add($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact1No($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact1Rel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact2Add($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact2No($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact2Rel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact3Add($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact3Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact3No($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereContact3Rel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereNocAbove($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereNocUnder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereSpouseIc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereSpouseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereSpouseStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereSpouseTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereSsnNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereTaxAbove($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Family whereTaxUnder($value)
 */
	class Family extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $month
 * @property int $day
 * @property string|null $status
 * @property int|null $country
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Holiday whereStatus($value)
 */
	class Holiday extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property int $vacancies
 * @property int $applicants
 * @property int $interviewed
 * @property int $hired
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereApplicants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereHired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereInterviewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereVacancies($value)
 */
	class Job extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobTitle whereName($value)
 */
	class JobTitle extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $appraisal_id
 * @property int $staff_id
 * @property int $manager_id
 * @property string $total_weightage
 * @property string $year
 * @property string $status
 * @property string $accepted
 * @property \Illuminate\Support\Carbon|null $accepted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $key_goal_1
 * @property string|null $weightage_1
 * @property string|null $key_goal_2
 * @property string|null $weightage_2
 * @property string|null $key_goal_3
 * @property string|null $weightage_3
 * @property string|null $key_goal_4
 * @property string|null $weightage_4
 * @property string|null $key_goal_5
 * @property string|null $weightage_5
 * @property string|null $indicator_measurement_1
 * @property string|null $indicator_measurement_2
 * @property string|null $indicator_measurement_3
 * @property string|null $indicator_measurement_4
 * @property string|null $indicator_measurement_5
 * @property-read \App\Models\Appraisal|null $appraisal
 * @property-read \App\Models\User $manager
 * @property-read \App\Models\User $staff
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereAppraisalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereIndicatorMeasurement1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereIndicatorMeasurement2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereIndicatorMeasurement3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereIndicatorMeasurement4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereIndicatorMeasurement5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereKeyGoal1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereKeyGoal2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereKeyGoal3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereKeyGoal4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereKeyGoal5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereTotalWeightage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereWeightage1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereWeightage2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereWeightage3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereWeightage4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereWeightage5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kpi whereYear($value)
 */
	class Kpi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nationality whereName($value)
 */
	class Nationality extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string $currency
 * @property string|null $min_salary
 * @property string|null $max_salary
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayGrade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayGrade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayGrade query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayGrade whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayGrade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayGrade whereMaxSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayGrade whereMinSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayGrade whereName($value)
 */
	class PayGrade extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|State whereName($value)
 */
	class State extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $username
 * @property string|null $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property string $access
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

