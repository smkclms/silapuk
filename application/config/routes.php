<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth/login'; // Mengarahkan ke halaman login
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Rute untuk autentikasi
$route['auth/login'] = 'auth/login';
$route['auth/authenticate'] = 'auth/authenticate';
$route['auth/logout'] = 'auth/logout';

// Rute untuk manajemen pengguna
$route['usermanagement'] = 'usermanagement/index';
$route['usermanagement/add'] = 'usermanagement/add'; // Tambahkan ini
$route['usermanagement/edit/(:num)'] = 'usermanagement/edit/$1'; // Jika Anda memiliki fungsi edit
$route['usermanagement/delete/(:num)'] = 'usermanagement/delete/$1'; // Jika Anda memiliki fungsi delete
$route['koderekening'] = 'koderekening/index'; // Rute untuk melihat kode rekening
$route['koderekening/add'] = 'koderekening/add'; // Rute untuk menambah kode rekening
$route['sumberanggaran'] = 'sumberanggaran/index'; // Rute untuk melihat sumber anggaran
$route['sumberanggaran/add'] = 'sumberanggaran/add'; // Rute untuk menambah sumber anggaran
$route['profil'] = 'profil/index';
$route['profil/password'] = 'profil/password';
$route['laporanpenggunaan'] = 'LaporanPenggunaan/index';
$route['laporanpenggunaan/cetak_pdf'] = 'LaporanPenggunaan/cetak_pdf';
$route['laporanpenggunaan/export_excel'] = 'LaporanPenggunaan/export_excel';
$route['laporanpenggunaan'] = 'laporanpenggunaan/index';
$route['laporanpenggunaan/cetak_pdf'] = 'laporanpenggunaan/cetak_pdf';
$route['laporanpenggunaan/export_excel'] = 'laporanpenggunaan/export_excel';
$route['dashboard/laporan_penggunaan_user'] = 'dashboard/laporan_penggunaan_user';
$route['anggaran/tambah'] = 'anggaran/tambah_tahun';
$route['anggaran/simpan_tahun'] = 'anggaran/simpan_tahun';





$route['report'] = 'report/index';
