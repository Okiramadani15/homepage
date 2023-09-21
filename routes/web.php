<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Homepage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Homepage\HomepageController::class, 'index']);
Route::get('/asset/{id}', [Homepage\HomepageController::class, 'detailAsset']);

Route::get('/aktifitas', [Homepage\ActivityController::class, 'index']);

Route::get('/aktifitas/detail', [Homepage\ActivityController::class, 'index']);

Route::get('/profile/sejarah', [Homepage\ProfileController::class, 'index']);
// Route::get('/profile/sejarah', function () {
//     return view('history.index');
// });
Route::get('/profile/visi-misi', [Homepage\ProfileController::class, 'visionMision']);
// Route::get('/profile/visi-misi', function () {
//     return view('vision-and-mission.index');
// });

Route::get('/hubungi-kami', [Homepage\MessageController::class, 'index'])->name('contact-us.index');
Route::post('/hubungi-kami/create', [Homepage\MessageController::class, 'create'])->name('contact-us.create');

Route::get('/struktur-yayasan', [Homepage\StructureController::class, 'index']);
// Route::get('/struktur-yayasan', function () {
//     return view('foundation-structure.index');
// });

Route::get('/kurikulum/peminatan/ips', [Homepage\CurriculumController::class, 'ips']);
// Route::get('/kurikulum/peminatan/ips', function () {
//     return view('curriculum-ips.index');
// });

Route::get('/kurikulum/peminatan/ipa', [Homepage\CurriculumController::class, 'ipa']);
// Route::get('/kurikulum/peminatan/ipa', function () {
//     return view('curriculum-ipa.index');
// });

Route::get('/kurikulum/tsnawiyah', [Homepage\CurriculumController::class, 'tsnawiyah']);
// Route::get('/kurikulum/tsnawiyah', function () {
//     return view('curriculum-tsanawiyah.index');
// });


Route::get('/kalender-pesantren', [Homepage\EducationalCalendarController::class, 'index']);
Route::get('/data-calendar', [Homepage\EducationalCalendarController::class, 'dataCalendar']);

Route::get('/mts', [Homepage\AcademicController::class, 'mts']);
// Route::get('/mts', function () {
//     return view('mts.index');
// });

Route::get('/ma', [Homepage\AcademicController::class, 'ma']);
// Route::get('/ma', function () {
//     return view('ma.index');
// });

Route::get('/raport-online', [Homepage\AcademicController::class, 'reportOnline']);
// Route::get('/raport-online', function () {
//     return view('online-report.index');
// });

Route::get('/fasilitas', [Homepage\FacilitiesController::class, 'index']);
// Route::get('/fasilitas', function () {
//     return view('facilities.index');
// });
// Route::get('/fasilitas-umum', function () {
//     return view('public-facilities.index');
// });

Route::get('/pendaftaran', [Homepage\RegisterController::class, 'index']);
// Route::get('/pendaftaran', function () {
//     return view('register.index');
// });
