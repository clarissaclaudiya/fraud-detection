<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FraudDetectionController;

// Rute untuk menyajikan halaman Dasbor Utama SPA (termasuk Login Page di dalamnya)
Route::get('/', function () {
    return view('dashboard');
});

// Rute API terintegrasi
Route::prefix('api')->group(function () {
    Route::post('/login', [FraudDetectionController::class, 'login']);
    Route::get('/dashboard-data', [FraudDetectionController::class, 'getDashboardData']);
    Route::get('/classes', [FraudDetectionController::class, 'getClasses']);
    Route::get('/students', [FraudDetectionController::class, 'getStudents']);
    Route::post('/attend', [FraudDetectionController::class, 'submitAttendance']);
    Route::post('/alerts/resolve', [FraudDetectionController::class, 'resolveAlert']);
    Route::post('/reset-demo', [FraudDetectionController::class, 'resetDemo']);

    // Modul LMS Tambahan
    Route::get('/materials', [FraudDetectionController::class, 'getMaterials']);
    Route::post('/materials', [FraudDetectionController::class, 'uploadMaterial']);
    Route::get('/assignments', [FraudDetectionController::class, 'getAssignments']);
    Route::post('/assignments', [FraudDetectionController::class, 'createAssignment']);
    Route::post('/assignments/submit', [FraudDetectionController::class, 'submitAssignment']);
    Route::get('/assignments/submissions', [FraudDetectionController::class, 'getAssignmentSubmissions']);
    Route::post('/assignments/grade', [FraudDetectionController::class, 'gradeAssignmentSubmission']);
    Route::get('/exams', [FraudDetectionController::class, 'getExams']);
    Route::post('/exams', [FraudDetectionController::class, 'createExam']);
    Route::get('/exams/{id}/questions', [FraudDetectionController::class, 'getExamQuestions']);
    Route::post('/exams/submit', [FraudDetectionController::class, 'submitExamAttempt']);
    Route::get('/exams/attempts', [FraudDetectionController::class, 'getExamAttempts']);
    Route::get('/grades', [FraudDetectionController::class, 'getAcademicGrades']);
});
