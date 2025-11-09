<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   mod_flashcards
 * @copyright 2019 RWTH Aachen (see README.md)
 * @author    Anna Heynkes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Meta information
$string['cardbox'] = 'Hộp Thẻ';
$string['activityname'] = 'Hoạt động Hộp thẻ';
$string['modulename'] = 'Hộp Thẻ';
$string['modulename_help'] = '<p>Hoạt động này cho phép bạn tạo thẻ ghi nhớ cho từ vựng, thuật ngữ kỹ thuật, công thức, v.v. mà bạn muốn ghi nhớ. Bạn có thể học với các thẻ như bạn làm với một hộp thẻ.</p><p>Thẻ có thể được tạo bởi mọi người tham gia, nhưng chỉ được sử dụng để luyện tập nếu giáo viên đã chấp nhận chúng.</p>';
$string['pluginname'] = 'Hộp Thẻ';
$string['modulenameplural'] = 'Các Hộp Thẻ';
$string['cardboxname'] = 'Tên của Hộp Thẻ này';
$string['pluginadministration'] = 'Quản lý Thẻ ghi nhớ';
$string['setting_autocorrection'] = 'Cho phép tự động chấm điểm';
$string['setting_autocorrection_help'] = 'Tự động chấm điểm chỉ hoạt động với văn bản thông thường. Nếu học sinh có thể được yêu cầu đưa ra câu trả lời công thức, bạn nên tắt tự động chấm điểm.';
$string['setting_autocorrection_label'] = '<font color="red">chỉ phù hợp với văn bản</font>'; // 'Activate with care.';
$string['setting_enablenotifications'] = 'Cho phép thông báo';
$string['setting_enablenotifications_help'] = 'Học sinh nhận thông báo khi thẻ được chỉnh sửa hoặc đã đến lúc luyện tập lại.';
$string['setting_enablenotifications_label'] = 'Bật gửi thông báo cho học sinh';
$string['necessaryanswers_activity'] = 'Cài đặt mặc định cho "Cần bao nhiêu câu trả lời?"';
$string['necessaryanswers_activity_help'] = 'Đặt giá trị mặc định cho "Cần bao nhiêu câu trả lời?" trong biểu mẫu tạo thẻ.';
$string['necessaryanswers_activity_locked'] = 'Cho phép thay đổi số câu trả lời cần thiết sau này?';
$string['necessaryanswers_activity_locked_help'] = 'Nếu chọn "Có", thì có thể thay đổi số lượng câu trả lời yêu cầu khi tạo hoặc chỉnh sửa thẻ.';
$string['casesensitive'] = 'Phân biệt chữ hoa/thường';
$string['casesensitive_help'] = 'Xác định xem khi luyện tập với kiểm soát tự động, các mục nhập chỉ khác với câu trả lời đúng về mặt chữ hoa/thường có được tính là đúng hay không.';
$string['numberofcardssetting'] = 'Số lượng thẻ để luyện tập';
$string['numberofcardssetting_help'] = 'Xác định số lượng thẻ mà học sinh nên học mỗi phiên luyện tập. Nếu chọn "Học sinh quyết định", họ có sự lựa chọn tự do.';
$string['studentschoose'] = 'Học sinh lựa chọn';
$string['messageprovider:changenotification'] = 'Thông báo khi một thẻ ghi nhớ được chỉnh sửa';
$string['changenotification:subject'] = 'Thông báo thay đổi';
$string['changenotification:message'] = 'Một thẻ ghi nhớ đã được chỉnh sửa trong hộp thẻ của bạn. Đây là thẻ ở dạng hiện tại.';

// Reminders
$string['send_practice_reminders'] = 'Gửi email nhắc nhở đến những người tham gia khóa học';
$string['messageprovider:memo'] = 'Lời nhắc luyện tập với hộp thẻ';
$string['remindersubject'] = 'Nhắc nhở luyện tập';
$string['remindergreeting'] = 'Xin chào {$a}, ';
$string['remindermessagebody'] = 'vui lòng nhớ học với hộp thẻ của bạn một cách thường xuyên.';
$string['reminderfooting'] = 'Lời nhắc này được gửi tự động bởi hộp thẻ "{$a->cardboxname}" của bạn trong khóa học "{$a->coursename}".';

// Tab navigation
$string['addflashcard'] = 'Thêm thẻ';
$string['practice'] = 'Luyện tập';
$string['statistics'] = 'Tiến độ';
$string['overview'] = 'Tổng quan';
$string['review'] = 'Xem xét';
$string['massimport'] = 'Nhập thẻ';
$string['edittopic'] = 'Quản lý chủ đề';

// Subpage titles
$string['titleforaddflashcard'] = 'Thẻ mới';
$string['titleforpractice'] = 'Luyện tập';
$string['titleforreview'] = 'Kiểm tra thẻ';
$string['titleforcardedit'] = 'Chỉnh sửa thẻ';
$string['intro:overview'] = 'Tổng quan này hiển thị tất cả các thẻ đã được phê duyệt.';

// Form elements for creating a new card
$string['choosetopic'] = 'Chủ đề';
$string['reviewtopic'] = 'CHỦ ĐỀ: ';
$string['notopic'] = 'chưa phân công';
$string['addnewtopic'] = 'tạo chủ đề';
$string['entertopic'] = 'tạo chủ đề';
$string['enterquestion'] = 'Câu hỏi hoặc lời nhắc';
$string['entercontextquestion'] = 'Thông tin bổ sung cho câu hỏi này';
$string['addcontext'] = 'Hiển thị ngữ cảnh';
$string['removecontext'] = 'Ẩn ngữ cảnh';
$string['entercontextanswer'] = 'Thông tin bổ sung cho câu trả lời';
$string['necessaryanswers_card'] = 'Cần bao nhiêu câu trả lời?';
$string['necessaryanswers_all'] = 'tất cả';
$string['necessaryanswers_one'] = 'một';
$string['addimage'] = 'Hiển thị tùy chọn hình ảnh';
$string['removeimage'] = 'Ẩn tùy chọn hình ảnh';
$string['image'] = 'Hình ảnh câu hỏi';
$string['imagedescription'] = 'Mô tả hình ảnh này cho người không thể nhìn thấy (khuyến nghị)';
$string['imgdescriptionnecessary_label'] = 'Hình ảnh này chỉ mang tính trang trí';
$string['addsound'] = 'Hiển thị tùy chọn âm thanh';
$string['removesound'] = 'Ẩn tùy chọn âm thanh';
$string['sound'] = 'Âm thanh câu hỏi';
$string['answerimage'] = 'Hình ảnh câu trả lời';
$string['answersound'] = 'Âm thanh câu trả lời';
$string['enteranswer'] = 'Giải pháp';
$string['answer_repeat'] = 'Thêm giải pháp khác';
$string['autocorrectlocked'] = 'Vô hiệu hóa Kiểm tra Tự động';
$string['autocorrecticon'] = 'Chỉ Tự kiểm tra';
$string['autocorrecticon_help'] = 'Câu trả lời không thể được nhập vào khi luyện tập ở chế độ Kiểm tra Tự động. Trong chế độ kiểm tra tự động, thẻ học sẽ vẫn được hiển thị, nhưng chỉ như một tự kiểm tra.';
$string['autocorrectlocked_help'] = 'Kích hoạt hộp kiểm này nếu câu trả lời của thẻ học không được nhập vào khi luyện tập ở chế độ "Kiểm tra tự động". Trong chế độ "Kiểm tra tự động", thẻ học sẽ vẫn được hiển thị, nhưng chỉ như một tự kiểm tra. Tùy chọn này đặc biệt hữu ích cho các thẻ học có câu trả lời không phù hợp cho việc nhập thủ công (ví dụ: định nghĩa), nhưng vẫn nên được luyện tập cùng với các thẻ học khác có câu trả lời được nhập thủ công.';
$string['answer_repeat_help'] = 'Nếu bạn có nhiều giải pháp, vui lòng sử dụng trường giải pháp riêng cho mỗi câu trả lời.<br>
                                Một trường giải pháp khác có thể được thêm bằng nút "Thêm giải pháp khác".<br>
                                Để thiết lập xem học sinh cần biết tất cả câu trả lời hay chỉ một (trong trường hợp câu trả lời thay thế) vui lòng sử dụng menu thả xuống bên dưới.';

$string['addanswer'] = 'Thêm giải pháp khác';
$string['autocorrectlocked'] = 'Vô hiệu hóa Kiểm tra Tự động';
$string['savecard'] = 'Lưu';
$string['saveandaccept'] = 'Lưu và chấp nhận';

// Success notifications
$string['success:addnewcard'] = 'Thẻ đã được tạo và đang chờ phê duyệt.';
$string['success:addandapprovenewcard'] = 'Thẻ đã được tạo và phê duyệt để luyện tập.';
$string['success:approve'] = 'Thẻ đã được phê duyệt và hiện có thể sử dụng tự do.';
$string['success:edit'] = 'Thẻ đã được chỉnh sửa.';
$string['success:reject'] = 'Thẻ đã bị xóa.';

// Error notifications
$string['error:updateafterreview'] = 'Cập nhật thất bại.';
$string['error:createcard'] = 'Thẻ không được tạo, vì thiếu câu hỏi và/hoặc câu trả lời hoặc nếu bạn tải lên hình ảnh thì có thể thiếu mô tả hình ảnh.';


// Import cards
$string['examplesinglecsv'] = 'Tệp văn bản mẫu cho thẻ có câu trả lời đơn';
$string['examplesinglecsv_help'] = 'Tệp văn bản mẫu cho thẻ có câu trả lời đơn';
$string['examplemulticsv'] = 'Tệp văn bản mẫu cho thẻ có nhiều câu trả lời';
$string['examplemulticsv_help'] = 'Tệp văn bản mẫu cho thẻ có nhiều câu trả lời';
$string['cancelimport'] = 'Nhập đã bị hủy';
$string['importpreview'] = 'Xem trước nhập thẻ';
$string['importsuccess'] = '{$a} thẻ được nhập thành công';
$string['allowedcolumns'] = '<br><p>Tên cột được phép là:</p>';
$string['ques'] = 'Tên cột cho câu hỏi';
$string['ans'] = 'Tên cột cho câu trả lời';
$string['qcontext'] = 'Tên cột cho ngữ cảnh câu hỏi';
$string['acontext'] = 'Tên cột cho ngữ cảnh câu trả lời';
$string['topic'] = 'Tên cột cho chủ đề';
$string['acdisable'] = 'Tên cột để vô hiệu hóa Kiểm tra Tự động cho thẻ. Có = 1; Không = 0.';

// Info notifications
$string['info:statisticspage'] = 'Trang này cho bạn biết có bao nhiêu thẻ trong hộp thẻ của bạn (đến hạn và chưa đến hạn) và bạn đã làm tốt như thế nào trong các phiên luyện tập trước.';
$string['info:nocardsavailableforreview'] = 'Hiện tại không có thẻ mới nào để xem xét.';
$string['info:waslastcardforreview'] = 'Đây là thẻ cuối cùng được xem xét.';
$string['info:nocardsavailableforoverview'] = 'Không có thẻ nào trong hộp thẻ này.';
$string['info:nocardsavailable'] = 'Hiện tại không có thẻ nào trong hộp thẻ của bạn.';
$string['help:nocardsavailable'] = 'Hộp thẻ trống';
$string['help:nocardsavailable_help'] = 'Những lý do có thể:<ul><li>Chưa có thẻ nào được tạo.</li><li>Giáo viên chưa kiểm tra và chấp nhận thẻ.</li></ul>';
$string['info:nocardsavailableforpractice'] = 'Không có thẻ nào sẵn sàng để luyện tập.';
$string['help:nocardsavailableforpractice'] = 'Không có thẻ';
$string['help:nocardsavailableforpractice_help'] = 'Bạn đã trả lời đúng mọi thẻ hiện có sẵn 5 lần trong khoảng thời gian ít nhất hai tháng. Những thẻ này được coi là đã thành thạo và không lặp lại nữa.';
$string['info:nocardsdueforpractice'] = 'Chưa có thẻ nào của bạn đến hạn lặp lại.';
$string['info:enrolledstudentsthreshold_manager'] = 'Phải có ít nhất {$a} học sinh đăng ký khóa học này để thống kê luyện tập hàng tuần được hiển thị.';
$string['info:enrolledstudentsthreshold_student'] = 'Tiến độ trung bình của học sinh chỉ được hiển thị nếu có ít nhất {$a} học sinh đăng ký khóa học.';
$string['help:nocardsdueforpractice'] = 'Không có thẻ đến hạn';
$string['help:nocardsdueforpractice_help'] = 'Thẻ mới đến hạn ngay lập tức. Đối với bất kỳ thẻ nào khác, bộ bài quyết định:<ol><li>bộ bài: hàng ngày</li><li>bộ bài: sau 3 ngày</li><li>bộ bài: sau 7 ngày</li><li>bộ bài: sau 16 ngày</li><li>bộ bài: sau 34 ngày</li></ol>';
$string['help:whenarecardsdue'] = 'Khi nào thẻ đến hạn';
$string['help:whenarecardsdue_help'] = 'Thẻ mới ngay lập tức đến hạn luyện tập. Đối với bất kỳ thẻ nào khác, bộ bài quyết định:<ol><li>bộ bài: hàng ngày</li><li>bộ bài: sau 3 ngày</li><li>bộ bài: sau 7 ngày</li><li>bộ bài: sau 16 ngày</li><li>bộ bài: sau 34 ngày</li></ol>';
$string['help:practiceanyway'] = 'Nếu bạn vẫn luyện tập, các thẻ được trả lời đúng không tiến lên, mà vẫn ở trong khay hiện tại của chúng.';

// Title and form elements for choosing the settings for a new practice session
$string['titleforchoosesettings'] = 'Tùy chọn luyện tập';
$string['choosecorrectionmode'] = 'Chế độ luyện tập';
$string['selfcorrection'] = 'Tự kiểm tra';
$string['autocorrection'] = 'Kiểm tra tự động';
$string['flashcardmode'] = 'Chế độ Flashcard';
$string['weightopic'] = 'Tập trung';
$string['notopicpreferred'] = 'không ưu tiên';
$string['practiceall'] = 'Luyện tập tất cả thẻ';
$string['practiceall_help'] = 'Những thẻ này không tiến lên bộ bài tiếp theo nếu được trả lời đúng. Do đó, bạn có thể luyện tập thường xuyên như mong muốn mà không có nguy cơ thẻ rời khỏi hộp thẻ vĩnh viễn chỉ sau vài ngày.';
$string['onlyonetopic'] = 'Chủ đề';
$string['maxnumbercardspractice'] = 'Số thẻ tối đa';
$string['undefined'] = 'Không giới hạn';

$string['beginpractice'] = 'Bắt đầu luyện tập';
$string['applysettings'] = 'Áp dụng';
$string['cancel'] = 'Hủy';

// Practice mode: Buttons.
$string['options'] = 'Luyện tập bằng mọi cách';
$string['endpractice'] = 'Kết thúc luyện tập';

$string['dontknow'] = "Tôi không biết";
$string['checkanswer'] = 'Kiểm tra';
$string['submitanswer'] = 'Trả lời';
$string['markascorrect'] = 'Đúng';
$string['markasincorrect'] = 'Sai';
$string['override'] = 'Ghi đè';
$string['override_iscorrect'] = 'Không, tôi đã đúng!';
$string['override_isincorrect'] = 'Không, tôi đã sai.';
$string['proceed'] = 'Tiếp theo';
$string['suggestanswer_label'] = 'Vui lòng đề xuất giải pháp mới';
$string['suggestanswer'] = 'Đề xuất câu trả lời';
$string['suggestanswer_send'] = 'Gửi câu trả lời';
$string['cardsleft'] = 'Thẻ còn lại:';
$string['nextcard'] = 'Thẻ Tiếp Theo';
$string['correct'] = 'Đúng rồi!';
$string['incorrect'] = 'Sai rồi';
$string['correctanswer'] = 'Đáp Án Đúng';
$string['explanation'] = 'Giải Thích';

$string['solution'] = 'Giải pháp';
$string['yoursolution'] = 'Câu trả lời của bạn';

// Practice mode: Feedback
$string['feedback:correctandcomplete'] = 'Làm tốt lắm!';
$string['feedback:incomplete'] = 'Thiếu câu trả lời!';
$string['feedback:correctbutincomplete'] = 'Còn thiếu {$a} câu trả lời.';
$string['feedback:incorrectandpossiblyincomplete'] = 'Không đúng!';
$string['feedback:notknown'] = 'Không có câu trả lời!';

$string['sessioncompleted'] = 'Hoàn thành! :-)';
$string['titleprogresschart'] = 'Kết quả';
$string['right'] = 'đúng';
$string['wrong'] = 'sai';
$string['titleoverviewchart'] = 'Hộp thẻ';
$string['new'] = 'mới';
$string['known'] = 'đã thành thạo';
$string['flashcards'] = 'thẻ';
$string['flashcardsdue'] = 'đến hạn';
$string['flashcardsnotdue'] = 'chưa đến hạn';
$string['box'] = 'hộp';

$string['titleperformancechart'] = 'Các phiên luyện tập trước';
$string['performance'] = '% đúng';

$string['titlenumberofcards'] = 'Số lượng thẻ mỗi phiên';
$string['numberofcards'] = 'Số lượng';
$string['numberofcardsavg'] = 'Trung bình';
$string['numberofcardsmin'] = 'Tối thiểu';
$string['numberofcardsmax'] = 'Tối đa';

$string['titledurationofasession'] = 'Thời lượng của một phiên';
$string['duration'] = 'Thời lượng (phút)';
$string['durationavg'] = 'Trung bình';
$string['durationmin'] = 'Tối thiểu';
$string['durationmax'] = 'Tối đa';


// Review.
$string['approve'] = 'Phê duyệt';
$string['reject'] = 'Từ chối';
$string['edit'] = 'Chỉnh sửa';
$string['skip'] = 'Bỏ qua';
$string['countcardapprove'] = '{&a} thẻ đã được phê duyệt và sẵn sàng để luyện tập';
$string['countcardreject'] = '{&a} thẻ đã bị từ chối';
$string['rejectcard'] = 'Từ chối Thẻ';
$string['rejectcardinfo'] = 'Bạn có muốn từ chối {$a} thẻ đã chọn không? Những thẻ này sẽ bị xóa và không thể khôi phục.';

$string['allanswersnecessary'] = "Tất cả";
$string['oneanswersnecessary'] = "Một";
$string['allanswersnecessary_help'] = "cần tất cả câu trả lời";
$string['oneanswersnecessary_help'] = "cần một câu trả lời";

// Statistics
$string['strftimedate'] = '%d. %B %Y';
$string['strftimedatetime'] = '%d. %b %Y, %H:%M';
$string['strftimedateshortmonthabbr'] = '%d %b';


$string['barchartxaxislabel'] = 'Bộ bài';
$string['barchartyaxislabel'] = 'Số lượng thẻ';
$string['barchartstatistic1'] = 'Số lượng thẻ mỗi bộ bài cho tất cả học sinh';
$string['linegraphxaxislabel'] = 'Ngày';
$string['linegraphyaxislabel_performance'] = '% đã biết';
$string['linegraphyaxislabel_numbercards'] = 'Số lượng thẻ';
$string['linegraphyaxislabel_duration'] = 'Thời lượng (phút)';
$string['linegraphtooltiplabel_below_threshold'] = 'không có thống kê vì <{$a} người dùng đã luyện tập tuần đó';
$string['lastpractise'] = 'luyện tập lần cuối';
$string['nopractise'] = 'chưa luyện tập';
$string['newcard'] = 'thẻ mới';
$string['knowncard'] = 'thẻ đã thành thạo';
$string['averagestudentscompare'] = 'trung bình của tất cả học sinh';
$string['absolutenumberofcards'] = 'Số lượng thẻ tuyệt đối';

$string['yes'] = 'Có';
$string['no'] = 'Không';
$string['cancel'] = 'Hủy';
$string['deletecard'] = 'Xóa thẻ?';
$string['deletecardinfo'] = 'Thẻ và tiến độ của thẻ này sẽ bị xóa cho tất cả người dùng.';
$string['delete'] = 'Xóa';


$string['topicfilter'] = 'Chủ đề ';
$string['deckfilter'] = 'Bộ bài';
$string['noselection'] = 'tất cả';
$string['createddate'] = 'Ngày tạo';
$string['alphabetical'] = 'Theo bảng chữ cái';
$string['sorting'] = 'Sắp xếp';
$string['descending'] = 'giảm dần';
$string['ascending'] = 'tăng dần';

$string['card'] = 'Câu hỏi/Câu trả lời:';
$string['cardposition'] = 'Bộ bài:';
$string['cardposition_help'] = 'Hiển thị bộ bài mà thẻ này đang ở. Số càng cao thì thẻ đã được học càng tốt. Thẻ mới chưa có trong hộp nào. Sau hộp 5, thẻ được coi là "đã thành thạo" và không còn được luyện tập nữa.';

// Overview Tab.
$string['student:deckdescription'] = 'Thẻ này nằm trong bộ bài {$a}';
$string['manager:deckdescription'] = 'Trung bình, thẻ này nằm trong bộ bài {$a} trong số tất cả học sinh';
$string['manager:repeatdesc'] = 'Thẻ này được học sinh thành thạo, trung bình, sau {$a} lần lặp lại';
$string['student:repeatdesc'] = 'Thẻ này được thành thạo sau {$a} lần lặp lại';

// Edit topics Tab.
$string['deletetopic'] = 'Xóa chủ đề';
$string['deletetopicinfo'] = 'Bạn có muốn xóa chủ đề đã chọn {$a} không? Đối với các thẻ được gán cho chủ đề này, điều này sẽ đặt chủ đề thành "chưa phân công".';
$string['createtopic'] = 'Thêm';
$string['existingtopics'] = 'chủ đề đã tồn tại';
$string['notopics'] = 'chưa có chủ đề nào';
$string['nulltopic'] = 'Chưa Phân công';


// Settings.
$string['statistics_heading'] = 'Thống kê';
$string['weekly_users_practice_threshold'] = 'Ngưỡng người luyện tập mỗi tuần';
$string['weekly_users_practice_threshold_desc'] = 'Bao nhiêu người dùng cần luyện tập mỗi tuần để người quản lý có thể thấy thống kê cho tuần đó.';
$string['weekly_enrolled_students_threshold'] = 'Ngưỡng học sinh đăng ký';
$string['weekly_enrolled_students_threshold_desc'] = 'Bao nhiêu học sinh cần đăng ký vào khóa học để thống kê hàng tuần được hiển thị cho người quản lý.';
$string['qmissing'] = 'Thiếu câu hỏi.';
$string['qfieldmissing'] = 'Thiếu trường câu hỏi.';
$string['amissing'] = 'Thiếu câu trả lời.';
$string['afieldmissing'] = 'Thiếu trường câu trả lời.';
$string['successmsg'] = ' thẻ đã được nhập thành công.';
$string['errormsg'] = 'Các dòng dưới đây không thể được nhập thành thẻ';
$string['status'] = 'trạng thái';
$string['continue'] = 'Tiếp tục';
$string['unmatchedanswers'] = 'Tệp CSV yêu cầu {$a->csvschema} câu trả lời; chỉ có {$a->actual} được đưa ra.';
$string['emptyimportfile'] = 'Không có gì để nhập - tệp CSV không có hàng nào.';

// Capabilities definitions.
$string['cardbox:approvecard'] = 'Phê duyệt thẻ';
$string['cardbox:deletecard'] = 'Xóa thẻ';
$string['cardbox:edittopics'] = 'Chỉnh sửa chủ đề';
$string['cardbox:seestatus'] = 'Xem trạng thái';
$string['cardbox:submitcard'] = 'Gửi thẻ';
$string['cardbox:view'] = 'Xem thẻ';
$string['cardbox:addinstance'] = 'Thêm Hộp thẻ mới';
$string['cardbox:practice'] = 'Luyện tập thẻ';

// Events.
$string['event:card_accepted'] = 'Thẻ được chấp nhận';
$string['event:card_created'] = 'Thẻ được tạo';
$string['event:card_deleted'] = 'Thẻ bị xóa';
$string['event:card_updated'] = 'Thẻ được cập nhật';
$string['event:practice_session_ended'] = 'Phiên luyện tập kết thúc';
$string['event:practice_session_started'] = 'Phiên luyện tập bắt đầu';

// Flashcard mode - 9 option placeholders
$string['option_placeholder_1'] = 'Lựa chọn A';
$string['option_placeholder_2'] = 'Lựa chọn B';
$string['option_placeholder_3'] = 'Lựa chọn C';
$string['option_placeholder_4'] = 'Lựa chọn D';
$string['option_placeholder_5'] = 'Lựa chọn E';
$string['option_placeholder_6'] = 'Lựa chọn F';
$string['option_placeholder_7'] = 'Lựa chọn G';
$string['option_placeholder_8'] = 'Lựa chọn H';
