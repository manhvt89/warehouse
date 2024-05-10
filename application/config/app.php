<?php defined('BASEPATH') OR exit('No direct script access allowed');
$config['cyls'] = array(
                            '-',
                            '0.00',
                            '0.25',
                            '0.50',
                            '0.75',
                            '1.00',
                            '1.25',
                            '1.50',
                            '1.75',
                            '2.00',
                            '2.25',
                            '2.50',
                            '2.75',
                            '3.00',
                            '3.25',
                            '3.50',
                            '3.75',
                            '4.00',
                            '4.25',
                            '4.50',
                            '4.75',
                            '5.00',
                            '5.25',
                            '5.50',
                            '5.75',
                            '6.00',

                        );
$config['mysphs'] = array(
                            '-',
                            '0.00',
                            '0.25',
                            '0.50',
                            '0.75',
                            '1.00',
                            '1.25',
                            '1.50',
                            '1.75',
                            '2.00',
                            '2.25',
                            '2.50',
                            '2.75',
                            '3.00',
                            '3.25',
                            '3.50',
                            '3.75',
                            '4.00',
                            '4.25',
                            '4.50',
                            '4.75',
                            '5.00',
                            '5.25',
                            '5.50',
                            '5.75',
                            '6.00',
                            '6.25',
                            '6.50',
                            '6.75',
                            '7.00',
                            '7.25',
                            '7.50',
                            '7.75',
                            '8.00',
                            '8.25',
                            '8.50',
                            '8.75',
                            '9.00',
                            '9.25',
                            '9.50',
                            '9.75',
                            '10.00',
                            '10.25',
                            '10.50',
                            '10.75',
                            '11.00',
                            '11.25',
                            '11.50',
                            '11.75',
                            '12.00',
                            '12.25',
                            '12.50',
                            '12.75',
                            '13.00',
                            '13.25',
                            '13.50',
                            '13.75',
                            '14.00',
                            '14.25',
                            '14.50',
                            '14.75',
                            '15.00',
                        );
$config['hysphs'] = array(
                            '-',
                            '0.00',
                            '0.25',
                            '0.50',
                            '0.75',
                            '1.00',
                            '1.25',
                            '1.50',
                            '1.75',
                            '2.00',
                            '2.25',
                            '2.50',
                            '2.75',
                            '3.00',
                            '3.25',
                            '3.50',
                            '3.75',
                            '4.00',
                            '4.25',
                            '4.50',
                            '4.75',
                            '5.00',
                            '5.25',
                            '5.50',
                            '5.75',
                            '6.00',
                            '6.25',
                            '6.50',
                            '6.75',
                            '7.00',
                            '7.25',
                            '7.50',
                            '7.75',
                            '8.00',
                        );
$config['map']  = array(
                            '-'=>0,
                            '0.00'=>1,
                            '0.25'=>2,
                            '0.50'=>3,
                            '0.75'=>4,
                            '1.00'=>5,
                            '1.25'=>6,
                            '1.50'=>7,
                            '1.75'=>8,
                            '2.00'=>9,
                            '2.25'=>10,
                            '2.50'=>11,
                            '2.75'=>12,
                            '3.00'=>13,
                            '3.25'=>14,
                            '3.50'=>15,
                            '3.75'=>16,
                            '4.00'=>17,
                            '4.25'=>18,
                            '4.50'=>19,
                            '4.75'=>20,
                            '5.00'=>21,
                            '5.25'=>22,
                            '5.50'=>23,
                            '5.75'=>24,
                            '6.00'=>25,
                            '6.25'=>26,
                            '6.50'=>27,
                            '6.75'=>28,
                            '7.00'=>29,
                            '7.25'=>30,
                            '7.50'=>31,
                            '7.75'=>32,
                            '8.00'=>33,
                            '8.25'=>34,
                            '8.50'=>35,
                            '8.75'=>36,
                            '9.00'=>37,
                            '9.25'=>38,
                            '9.50'=>39,
                            '9.75'=>40,
                            '10.00'=>41,
                            '10.25'=>42,
                            '10.50'=>43,
                            '10.75'=>44,
                            '11.00'=>45,
                            '11.25'=>46,
                            '11.50'=>47,
                            '11.75'=>48,
                            '12.00'=>49,
                            '12.25'=>50,
                            '12.50'=>51,
                            '12.75'=>52,
                            '13.00'=>53,
                            '13.25'=>54,
                            '13.50'=>55,
                            '13.75'=>56,
                            '14.00'=>57,
                            '14.25'=>58,
                            '14.50'=>59,
                            '14.75'=>60,
                            '15.00'=>61,
                        );

$config['exclude_module'] = array(
    'secure_controller',
    'no_access',
    'login',
    'testex',
    'home',
    'persons',
    'cron',
    'verify'
);

/*
** config qrcode; = 0 don't active qrcode; 
** = 1: Active module qrcode;
*/
$config['qrcode'] = 1;
$config['barcode'] = 1;

/*
Báo cáo Khác;
*/
/*
$config['filter'] = array('GONG 1T','GONG 2T','GONG 3T','GONG 4T','GONG 5T','GONG 5T+','G07','G08','G09','G10','G11','G12','G13','G14','G15',
        'G01',
        'G02',
        'G03',
        'G04',
        'G05',
        'G06'
        );

$config['filter_sun_glasses'] = array('MAT 1T','MAT 2T','MAT 3T','MAT 4T','MAT 5T','MAT 5T+','MAT 6T','M01','M02','M03','M04','M05','M06','M07','M08','M09','M10','M11','M12','M13','VẬT TƯ','M.HOYA','G.CHEMI',
      'Ngâm-Nhỏ',
      'Lens Seed 1M Trong',
        'Lens Seed 1M Pure',
        'Lens Seed 1D Rich',
        'Lens Seed 1D Base',
        'Lens Seed 1D Pure',
        'CLALEN 1D Latin',
        'CLALEN 1D Alica Brown',
        'CLALEN 1D Soul Brown',
        'CLALEN 1D Suzy Gray',
        'Lens Biomedics 1D',
        'Lens Biomedics55 3M'
    );

$config['filter_contact_lens'] = array(
        'Ngâm-Nhỏ',
        'Lens Seed 1M Trong',
        'Lens Seed 1M Pure',
        'Lens Seed 1D Rich',
        'Lens Seed 1D Base',
        'Lens Seed 1D Pure',
        'CLALEN 1D Latin',
        'CLALEN 1D Alica Brown',
        'CLALEN 1D Soul Brown',
        'CLALEN 1D Suzy Gray',
        'Lens Biomedics 1D',
        'Lens Biomedics55 3M');
        */

//TRạng thái PO

$config['caPOStatus'] = array(
    0=>'Mới tạo', // Có thể sửa
    1=>'Yêu cầu sửa lại', // Có thể sửa
    2=>'Đang chờ duyệt',
    3=>'Đã duyệt',
    4=>'Đã gửi đến nhà cung cấp',
    5=>'Đang nhập',
    6=>'Đã nhập 100%'
);
//Trạng thái Test
$config['caTestStatus'] = array(
    1=>'Mới tạo',
    2=>'Đã có thông tin bệnh',
    3=>'Đã mua hàng'
);
// Trạng thái đơn hàng 
$config['caOSStatus'] = array(
    1=>'Mới tạo', // Tạo mới đơn hàng và xuất hàng
    2=>'Hoàn thành'
);
/**
 *  BARCODE CONFIG
 */
/**
 * T3X105
 * 
 */
$config['Thuoc'] = [ 
    'template'=>'T3X105' //move setting
];
/**
 * G2X105
 * G1X75
 */
$config['GBarcode'] = array(
    'template'=>'G2X105' //move setting
);
$config['G1Barcode'] = [
    //'template'=>'G2X2X105' //move setting
    'template'=>''
];
/**
 * M3X105
 * M2X75
 */
$config['MBarcode'] = array(
    'template'=>'M3X105' //move setting
);
$config['Phone_Barcode'] = ''; //Số điện thoại: 0904642141 //move setting
$config['Slogan_Barcode'] = ''; //Số điện thoại //move setting
$config['Location_Barcode'] = ''; //Cơ sở 1 //move setting

$config['default_city'] = 'Lào Cai'; //move setting
/**
 * BEGIN Invoice - 
 */
$config['company_name_display'] = 0; // Hiển thị tên cửa hàng trên hóa đơn bán hàng; 0: Không hiển thị
 /**
 * END Invoice - 
 */

/**
 *  DON KHAM-----------------------------------------------------------------------
 * move setting
 */
$config['has_prescription'] = 1; 

//---- Begin Header
$config['test_header'] = 1; //1: Hiển thị header đơn kính; 0: Không hiển thị;
$config['ten_phong_kham'] = "PHÒNG KHÁM MẮT - ThS.Bác Sỹ CKII Lê Thị Hiền";
$config['lien_he'] = '0904.642.141 - 0971.278.065';
//----- End Header

$config['VA_khong_kinh'] = 1;
$config['VA_kinh_lo'] = 1;
$config['kinh_cu'] = 1;
$config['don_thuoc'] = 1;

$config['loai_mat_kinh'] = 1; // Hiển thị loại mắt

$config['hien_thi_VA'] = 1; // Hiển thị không kính & kính lỗ

$config['hien_thi_kinh_cu'] = 1; //Hiển thị thêm kính cũ


// ----- BEGIN FOOTER
$config['hien_thi_ten_bac_si'] = 1; // Hiển thị tên bác sĩ; 0: Không hiển thị
$config['ten_bac_si'] = 'Ths.BS CKII Lê Thị Hiền';
$config['test_display_nurse'] = 0; // Không hiển thị Y tá; 1: Hiển thị
$config['test_display_kxv'] = 0; // Không hiển thị Khúc xạ viên; 1: hiển thị
// ----- END FOOTER



$config['duration_dvts'] = array(
    'Ngày'=>'Ngày',
    'Tuần'=>'Tuần',
    'Tháng'=>'Tháng'
);

$config['reminder_status'] = [
    '0'=>'Chưa liên hệ',
    '1'=>'Sai số điện thoại',
    '2'=>'Chưa liên lạc được',
    '3' => 'Chưa sắp xếp được thời gian',
    '4'=>'Đã đặt lịch'
    //'Đã khám'
];


    
/**
 * END DON KHAM-----------------------------------------------------------------------
 */
/**
 * API URL
 */
//$config['api_url'] = 'https://apicuongdat.thiluc2020.com';
$config['api_url'] = 'https://tongkho.thiluc2020.com';

$config['debugging_mode'] = true;