<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Academic;
use App\Models\Semester;
use App\Models\LoginLog;
use App\Models\Course;
use App\Models\YearLevel;
use Mews\Captcha\Facades\Captcha;


class HomeController extends Controller
{
    public function index()
    {  
        //get academic data
        $activeAcademics = Academic::where('status', '1')->first();
        $activeSemester= Semester::where('sem_status', '1')->first();

        if (!empty($activeAcademics) && !empty($activeSemester)) {
            $academics = $activeAcademics->academic_start . '-' . $activeAcademics->academic_end;
            $semester = $activeSemester->semester_name;
        }

        $academicLists = Academic::select('academic_start', 'academic_end')->get();
        $semesterLists = Semester::select('semester_name')->get();
        $reportsChart = [];
        foreach ($academicLists as $academicList) {
            $academic = $academicList->academic_start . '-' . $academicList->academic_end;
            $reportsChart['xaxis'][] = $academic;
            foreach ($semesterLists as $semesterList) {
                $semester_id = $semesterList->semester_name;
                $reportsChart['series'][$semester_id][$semester_id] = $semester_id;
                $studentsEnrolled = Student::where('semester_id', '=', $semester_id)
                    ->where('course_id', '!=', 0)
                    ->where('year_id', '!=', 0)
                    ->where('section_id', '!=', 0)
                    ->where('id_number', '!=', 0)
                    ->where('academic', '=', $academic)
                    ->count();
                $reportsChart['series'][$semester_id]['data'][] = $studentsEnrolled;
            }
        }

        //count total students
        $studentCounts = Student::selectRaw("
                COUNT(*) as totalStudentsEnrolled,
                SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as totalMaleStudentsEnrolled,
                SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as totalFemaleStudentsEnrolled
            ")
            ->where('semester_id', '=', $semester)
            ->where('course_id', '!=', 0)
            ->where('year_id', '!=', 0)
            ->where('section_id', '!=', 0)
            ->where('id_number', '!=', 0)
            ->where('academic', '=', $academics)
            ->first();

        // Access the countsx
        $totalStudentsEnrolled = $studentCounts->totalStudentsEnrolled;
        $totalMaleStudentsEnrolled = $studentCounts->totalMaleStudentsEnrolled;
        $totalFemaleStudentsEnrolled = $studentCounts->totalFemaleStudentsEnrolled;
        //get all users
        $usersActivities = LoginLog::join('users', 'login_logs.attemp', '=', 'users.username')
        ->orderBy('login_logs.id', 'DESC')
        ->limit(15)
        ->get();
    
        
        // get all courses
        $courses = Course::all();
        $allCourses = [];
        $studentsPerCourse = [];

        if (!empty($courses)) {
            foreach ($courses as $course) {
                $allCourses[] = $course->course_code;

                $studentsEnrolled = Student::where('semester_id', '=', $semester)
                    ->where('year_id', '!=', 0)
                    ->where('section_id', '!=', 0)
                    ->where('id_number', '!=', 0)
                    ->where('academic', '=', $academics)
                    ->where('course_id', $course->course_id)
                    ->count();
                
                $studentsPerCourse[$course->course_code] = $studentsEnrolled;
            }
        }

        $coursesAndYearLevels = YearLevel::join('course', 'year_lvl.course_id', '=', 'course.course_id')->get();
        $departmentChats = [];
        foreach ($coursesAndYearLevels as $courseAndYearLevel) {
            $course_id = $courseAndYearLevel->course_id;
            $year_id = $courseAndYearLevel->year_id;
            $studentsEnrolled = Student::where('semester_id', '=', $semester)
                ->where('section_id', '!=', 0)
                ->where('id_number', '!=', 0)
                ->where('academic', '=', $academics)
                ->where('course_id', $course_id)
                ->where('year_id', $year_id)
                ->count();
        
            $departmentChats[$courseAndYearLevel->year_name][] = $studentsEnrolled;
        }
        

        $data = [
            'totalStudentsEnrolled' => $totalStudentsEnrolled ?? 0,
            'totaMaleStudentsEnrolled' => $totalMaleStudentsEnrolled ?? 0,
            'totalFemaleStudentsEnrolled' => $totalFemaleStudentsEnrolled ?? 0,
            'academics' => $academics ?? '',
            'semester' => $semester ?? '',
            'allCourses' => $allCourses ?? [],
            'reportsChart' => $reportsChart ?? [],
            'usersActivities' => $usersActivities ?? [],
            'departmentChats' => $departmentChats ?? [],
            'studentsPerCourse' => $studentsPerCourse ?? [],
        ];
        // Passing data to the view as an array
        return view('home.index', $data);
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => Captcha::img()]);
    }

}
