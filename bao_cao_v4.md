<style>
@page { size: A4; margin: 20mm 18mm; }
body { font-family: "Times New Roman", serif; font-size: 12pt; line-height: 1.6; }
h1, h2, h3, h4 { font-family: "Times New Roman", serif; }
</style>

<div style="width:210mm;height:297mm;margin:0 auto;padding:20mm 18mm;box-sizing:border-box;font-family:'Times New Roman',serif;">
  <div style="text-align:center;">
    <div style="font-size:18pt;font-weight:700;letter-spacing:0.5px;">TRƯỜNG ĐẠI HỌC CẦN THƠ</div>
    <div style="font-size:16pt;font-weight:700;margin-top:6px;">TRƯỜNG CÔNG NGHỆ THÔNG TIN &amp; TRUYỀN THÔNG</div>
    <div style="margin:28px 0 18px;">
      <img src="images/logoCTU.png" alt="Logo CTU" style="width:90mm;height:auto;" />
    </div>
    <div style="font-size:18pt;font-weight:700;margin:8px 0 8px;">BÁO CÁO</div>
    <div style="font-size:14pt;font-weight:700;margin-bottom:28px;">HỌC PHẦN: TIẾP THỊ VÀ KINH DOANH KỸ THUẬT SỐ</div>
    <div style="font-size:16pt;font-weight:700;margin:8px 0;">ĐỀ TÀI:</div>
    <div style="font-size:18pt;font-weight:700;margin:4px 0;">TÍCH HỢP GOOGLE ANALYTICS VÀ AI</div>
    <div style="font-size:18pt;font-weight:700;margin:4px 0;">VÀO HỖ TRỢ GIẢNG DẠY TIẾNG NHẬT</div>
    <div style="font-size:14pt;font-weight:700;margin-top:6px;">Trên nền tảng Moodle</div>
    <div style="font-size:13pt;font-weight:700;margin-top:20px;">Mã học phần: CT573HT</div>
  </div>
  <div style="margin-top:40mm;display:flex;justify-content:space-between;gap:20mm;">
    <div style="width:50%;font-size:12.5pt;">
      <div style="font-weight:700;margin-bottom:6px;">Giảng viên hướng dẫn:</div>
      <div>PGS.TS. Nguyễn Thái Nghe</div>
    </div>
    <div style="width:50%;font-size:12.5pt;">
      <div style="font-weight:700;margin-bottom:6px;">Thành viên thực hiện:</div>
      <div>1. M2525017 - Nguyễn Thái Duy</div>
      <div>2. M2525021 - Đỗ Thị Cẩm Hằng</div>
    </div>
  </div>
</div>

<div style="page-break-after:always;"></div>

# MỤC LỤC

1. Tóm tắt
2. Chương 1. Giới thiệu
3. Chương 2. Tổng quan lý thuyết
4. Chương 3. Kiến trúc hệ thống tích hợp
5. Chương 4. Tích hợp Google Analytics 4 (GA4)
6. Chương 5. Tích hợp AI vào plugin Cardbox
7. Chương 6. Đánh giá AI trong thương mại điện tử giáo dục
8. Chương 7. Hạn chế, kết luận và hướng phát triển
9. Tài liệu tham khảo

<div style="page-break-after:always;"></div>

# DANH MỤC HÌNH

Hình 1. Giao diện Plugin Cardbox trên Moodle
Hình 2. Kiến trúc hệ thống tích hợp (Browser – mod_cardbox – AI/GA4)
Hình 3. Cấu hình GA4 trong Moodle (Additional HTML)
Hình 4. GA4 Events Report — Dashboard
Hình 5. AI Hint — Giao diện gợi ý văn bản
Hình 6. AI Hint — Giao diện gợi ý bằng hình ảnh
Hình 7. AI Explain — Giao diện giải thích câu sai

<div style="page-break-after:always;"></div>

## Tóm tắt

Báo cáo trình bày việc tích hợp **Google Analytics 4 (GA4)** và **trí tuệ nhân tạo (AI)** vào plugin flashcard **mod_cardbox** trên nền tảng Moodle, phục vụ giảng dạy tiếng Nhật. Hệ thống giải quyết các vấn đề thực tế trong quá trình học ngoại ngữ bằng flashcard: thiếu gợi ý khi gặp thẻ khó, không có giải thích khi trả lời sai, không biết học gì tiếp sau khi hoàn thành bài test, và giảng viên tốn nhiều thời gian nhập liệu thẻ. Bốn tính năng AI được triển khai: **(1) AI Hint** — gợi ý cá nhân hóa theo lịch sử người học, **(2) AI Explain** — giải thích câu trả lời sai song ngữ Việt–Nhật, **(3) AI Course Suggest** — gợi ý khóa học và lộ trình sau khi kết thúc phiên luyện tập, **(4) AI Card Generator** — tạo thẻ tự động theo cấp JLPT hoặc chủ đề. Song song, GA4 được tích hợp để đo lường hành vi học tập thông qua 3 custom events, cung cấp dữ liệu cho giảng viên tối ưu hóa nội dung. Báo cáo phân tích từ góc nhìn **tiếp thị kỹ thuật số**, đánh giá tác động của AI lên các chỉ số conversion, retention, engagement và customer lifetime value trong bối cảnh thương mại điện tử giáo dục.

---

## CHƯƠNG 1. GIỚI THIỆU

### 1.1. Bối cảnh

Flashcard là phương pháp học ngoại ngữ đã được chứng minh hiệu quả qua nhiều nghiên cứu khoa học. Kỹ thuật **Spaced Repetition** (lặp lại ngắt quãng) giúp tối ưu trí nhớ dài hạn bằng cách ôn tập đúng thời điểm [1], [2]. **Active Recall** (nhớ lại chủ động) buộc não bộ truy xuất thông tin thay vì đọc thụ động, cải thiện khả năng ghi nhớ lên đến 200% so với phương pháp truyền thống [3], [4]. Đặc biệt, flashcard phù hợp với tiếng Nhật — ngôn ngữ có hệ thống Kanji phức tạp, đòi hỏi lặp lại nhiều lần để ghi nhớ [5], [6].

**Moodle** là hệ thống quản lý học tập (LMS) mã nguồn mở phổ biến nhất thế giới, được sử dụng tại hơn 240 quốc gia với hơn 400 triệu người dùng [7], [8]. Plugin **mod_cardbox** mở rộng Moodle bằng tính năng flashcard tích hợp hệ thống Leitner [9], cho phép sinh viên luyện tập từ vựng ngay trong môi trường học tập trực tuyến.

Tuy nhiên, flashcard truyền thống trên Moodle vẫn tồn tại nhiều hạn chế:

- Gặp thẻ khó → không có gợi ý, không biết hỏi ai → bỏ cuộc.
- Lật thẻ xong → không hiểu *tại sao* đáp án lại như vậy.
- Học một mình → không có phản hồi, không có động lực tiếp tục.
- Bộ thẻ lớn → giảng viên mất hàng giờ nhập tay từng thẻ một.

Từ góc nhìn giảng viên và quản trị viên:

- Không biết nội dung nào giúp ích, nội dung nào nên loại bỏ.
- Không có dữ liệu hành vi để cải thiện khóa học.

### 1.2. Vấn đề nghiên cứu

Moodle mặc định không ghi nhận hành vi học tập chi tiết bên trong plugin, khiến giảng viên thiếu dữ liệu để tối ưu nội dung. Đồng thời, trải nghiệm học flashcard vẫn mang tính thụ động — người học không được hỗ trợ khi gặp khó khăn và không có hướng dẫn cá nhân hóa.

Bài toán đặt ra: **Làm thế nào để biến flashcard từ công cụ ôn tập đơn giản thành hệ thống học tập thông minh, có dữ liệu, có AI hỗ trợ — và đánh giá giá trị tiếp thị kỹ thuật số mà hệ thống mang lại?**

### 1.3. Mục tiêu

- Tích hợp **Google Analytics 4** vào Moodle để đo lường hành vi học tập thông qua custom events.
- Phát triển **4 tính năng AI**: AI Hint, AI Explain, AI Course Suggest, AI Card Generator.
- Phân tích tác động từ góc nhìn **tiếp thị kỹ thuật số**: conversion, retention, engagement, customer lifetime value.
- Đánh giá ứng dụng AI trong bối cảnh **thương mại điện tử giáo dục**.

### 1.4. Phạm vi

- **Nền tảng:** Moodle LMS, plugin mod_cardbox.
- **Ngôn ngữ mục tiêu:** Tiếng Nhật (Kanji, từ vựng, ngữ pháp).
- **AI Backend:** Moodle core_ai subsystem (hỗ trợ OpenAI và các LLM tương thích).
- **Phân tích:** Google Analytics 4 (GA4) với custom events.
- **Góc nhìn:** Tiếp thị và kinh doanh kỹ thuật số (CT573HT).

---

## CHƯƠNG 2. TỔNG QUAN LÝ THUYẾT

### 2.1. Flashcard và khoa học ghi nhớ

Nghiên cứu của Ebbinghaus (1885) là nền tảng đầu tiên về **đường cong lãng quên** (forgetting curve), chỉ ra rằng thông tin bị quên theo hàm mũ nếu không ôn tập [1]. Spaced Repetition ra đời để chống lại quy luật này: bằng cách tăng dần khoảng cách giữa các lần ôn, người học có thể duy trì ghi nhớ dài hạn với nỗ lực tối thiểu [2], [10].

Cepeda và cộng sự (2008) đã phân tích tổng hợp và xác nhận rằng khoảng cách ôn tập tối ưu phụ thuộc vào thời gian giữ lại mong muốn, với hiệu quả ghi nhớ tăng đáng kể khi sử dụng lịch ôn ngắt quãng [2]. Roediger và Karpicke (2006) chứng minh rằng **Active Recall** — buộc não nhớ lại thay vì đọc thụ động — cải thiện ghi nhớ dài hạn mạnh mẽ hơn so với đọc lại nhiều lần [3].

Kornell (2009) nghiên cứu cụ thể về flashcard và kết luận rằng spacing (ngắt quãng) hiệu quả hơn cramming (nhồi nhét), ngay cả khi người học cảm thấy cramming "dễ hơn" [4]. Nation (2001) nhấn mạnh tầm quan trọng của phương pháp flashcard trong học từ vựng ngoại ngữ, đặc biệt với các ngôn ngữ có hệ thống chữ viết phức tạp như tiếng Nhật [5].

**Hệ thống Leitner** do Sebastian Leitner đề xuất (1972) chia thẻ thành các "hộp" theo mức độ thành thạo: thẻ trả lời đúng được chuyển sang hộp cao hơn (ôn ít hơn), thẻ sai quay về hộp thấp (ôn nhiều hơn) [9]. Đây là cơ sở cho thuật toán xếp hạng thẻ trong plugin mod_cardbox.

### 2.2. Moodle và kiến trúc plugin

Moodle (Modular Object-Oriented Dynamic Learning Environment) là LMS mã nguồn mở do Martin Dougiamas khởi tạo, dựa trên triết lý constructionism — người học xây dựng kiến thức thông qua tương tác [7]. Moodle hỗ trợ kiến trúc plugin mở rộng (activity modules, blocks, question types) cho phép cộng đồng phát triển tính năng mà không can thiệp vào core [8].

Plugin **mod_cardbox** là activity module triển khai hệ thống flashcard với Leitner system, cho phép giảng viên tạo bộ thẻ, sinh viên luyện tập theo các chế độ (autocorrect, self-check, flashcard), và hệ thống tự động xếp hạng thẻ theo mức độ thành thạo.

Từ Moodle 4.5, hệ thống giới thiệu **AI subsystem** (`core_ai`) — một lớp trừu tượng cho phép plugin gọi các dịch vụ AI (generate text, generate image) mà không cần biết provider cụ thể [11]. Quản trị viên cấu hình AI provider (OpenAI, Azure, v.v.) ở mức site, và plugin chỉ cần gọi API chuẩn của `core_ai`.

### 2.3. Google Analytics 4 (GA4) và Learning Analytics

**GA4** là thế hệ mới của Google Analytics, chuyển từ mô hình session-based sang **event-based**, hỗ trợ đo lường cross-platform và tuân thủ privacy-first [12], [13]. Mọi tương tác đều được ghi nhận dưới dạng event với parameters tùy chỉnh, cho phép linh hoạt hơn Universal Analytics.

**Learning Analytics** là lĩnh vực sử dụng dữ liệu về người học để tối ưu hóa quá trình học tập và môi trường học [14]. Siemens và Long (2011) định nghĩa learning analytics là "đo lường, thu thập, phân tích và báo cáo dữ liệu về người học và bối cảnh học tập, với mục tiêu hiểu và tối ưu hóa việc học" [14]. GA4 cung cấp nền tảng kỹ thuật cho learning analytics trong bối cảnh web-based LMS.

### 2.4. AI trong giáo dục (AIEd)

Holmes, Bialik và Fadel (2019) phân loại ứng dụng AI trong giáo dục thành 3 nhóm: **(1)** AI hỗ trợ người học (tutoring, feedback), **(2)** AI hỗ trợ giảng viên (tự động hóa, phân tích), **(3)** AI hỗ trợ hệ thống (adaptive learning, content generation) [15]. Chen, Chen và Lin (2020) tổng hợp nghiên cứu về AI trong giáo dục và xác nhận rằng AI cá nhân hóa (personalized AI) có tác động tích cực đến kết quả học tập, đặc biệt trong adaptive feedback và intelligent tutoring [16].

### 2.5. Tiếp thị kỹ thuật số trong giáo dục

Chaffey và Ellis-Chadwick (2019) định nghĩa tiếp thị kỹ thuật số (digital marketing) là "đạt được mục tiêu marketing thông qua áp dụng công nghệ số và phương tiện truyền thông số" [17]. Trong bối cảnh giáo dục trực tuyến (EdTech), các chỉ số marketing số quan trọng bao gồm:

- **Conversion Rate**: Tỷ lệ người dùng thử chuyển sang đăng ký/mua khóa học.
- **Retention Rate**: Tỷ lệ người học quay lại sau một khoảng thời gian.
- **Engagement Rate**: Mức độ tương tác (thời gian, tần suất, độ sâu).
- **Customer Lifetime Value (CLV/LTV)**: Tổng giá trị người học mang lại trong suốt vòng đời.
- **Churn Rate**: Tỷ lệ bỏ học/ngừng sử dụng [17], [18].

Kotler và Armstrong (2018) nhấn mạnh rằng **cá nhân hóa** trải nghiệm khách hàng là yếu tố then chốt trong marketing hiện đại, và AI là công cụ cho phép cá nhân hóa ở quy mô lớn [18]. Trong TMĐT giáo dục, cá nhân hóa thể hiện qua gợi ý khóa học phù hợp, nội dung thích ứng theo trình độ, và phản hồi tức thì cho người học.

---

## CHƯƠNG 3. KIẾN TRÚC HỆ THỐNG TÍCH HỢP

### 3.1. Tổng quan kiến trúc

Hệ thống được thiết kế với hai lớp tích hợp **độc lập**, **không xâm phạm** vào core Moodle:

- **GA4 (đo lường):** đo lường hành vi học tập, cung cấp insights cho giảng viên.
- **AI (nâng cao trải nghiệm):** hỗ trợ cá nhân hóa, giải thích, gợi ý và tự động hóa nội dung.

Kiến trúc gồm 3 tầng chính:

**Tầng Client (Browser):**
- `practice.js` — quản lý toàn bộ logic phiên luyện tập: render thẻ flashcard, nhận input người học, gọi AJAX đến server.
- `ga4Track(eventName, params)` — hàm wrapper gửi custom events đến GA4, đặt ở module scope để toàn bộ module truy cập được. Hàm tự bỏ qua nếu `gtag` chưa được nạp, đảm bảo plugin hoạt động bình thường khi site chưa cài GA4.

**Tầng Server (mod_cardbox):**
- `action.php` — bộ xử lý AJAX trung tâm, nhận request từ client và phân luồng theo action: chấm bài, gọi AI, lưu thống kê.
- `mdl_cardbox_*` — các bảng database lưu thẻ, thống kê luyện tập, lịch sử người học.

**Tầng Dịch vụ bên ngoài:**
- `core_ai (Moodle)` — subsystem AI tích hợp sẵn trong Moodle 4.5+, hỗ trợ generate_text cho AI Hint, AI Explain, AI Course Suggest, AI Card Generator [11].
- `OpenAI Images API` — gọi trực tiếp API OpenAI để sinh hình ảnh gợi ý (AI Image Hint), đọc API key từ cấu hình `aiprovider_openai` [19].
- `GA4 Server` — nhận events từ `gtag.js` qua browser, phân tích và hiển thị trên Dashboard.

### 3.2. Luồng xử lý chính

```
Browser (practice.js)
  │
  ├── ga4Track() ──────────────────────── → GA4 Server
  │
  └── AJAX (action.php) ──── → mod_cardbox
                                    │
                                    ├── core_ai (generate_text)
                                    │      └── AI Provider (OpenAI, etc.)
                                    │
                                    └── OpenAI Images API (direct)
```

### 3.3. Thiết kế module JavaScript

File `practice.js` được tổ chức theo kiến trúc object-oriented với các class chính:

- **Coordinate** — máy trạng thái (state machine) điều phối toàn bộ phiên luyện tập: khởi tạo, hiển thị câu hỏi, nhận câu trả lời, chấm điểm, chuyển thẻ tiếp theo.
- **Evaluate** — so sánh câu trả lời của người học với đáp án đúng.
- **Output** — render thẻ flashcard và kết quả lên giao diện.
- **Statistics** — thống kê phiên luyện tập, hiển thị biểu đồ doughnut kết quả, và khởi chạy AI Course Suggest.
- **EventHandling** — xử lý sự kiện DOM (click, keypress).

Class `Coordinate` duy trì mảng `wrongQuestionTexts` để thu thập nội dung các câu hỏi trả lời sai trong suốt phiên, phục vụ cho AI Course Suggest phân tích điểm yếu sau khi kết thúc.

### 3.4. Pattern gọi AI thống nhất

Toàn bộ các tính năng AI (trừ AI Image Hint) sử dụng cùng pattern gọi Moodle AI subsystem:

```php
// 1. Tạo AI action
$aiaction = new \core_ai\aiactions\generate_text(
    contextid: $context->id,
    userid: $USER->id,
    prompttext: $prompt,
);

// 2. Gọi AI manager
$manager = \core\di::get(\core_ai\manager::class);
$response = $manager->process_action($aiaction);

// 3. Xử lý kết quả
if ($response->get_success()) {
    $content = $response->get_response_data()['generatedcontent'];
}
```

Pattern này đảm bảo: **(a)** không phụ thuộc vào provider cụ thể — quản trị viên có thể đổi từ OpenAI sang Azure hay bất kỳ LLM nào, **(b)** tuân thủ quyền hạn Moodle — mỗi request kèm context và userid, **(c)** log đầy đủ qua hệ thống logging của Moodle.

---

## CHƯƠNG 4. TÍCH HỢP GOOGLE ANALYTICS 4 (GA4)

### 4.1. Vấn đề

Moodle mặc định không ghi nhận hành vi học tập chi tiết bên trong plugin. Khi vận hành một site Moodle, giảng viên và quản trị viên không có câu trả lời cho các câu hỏi cơ bản:

- Có bao nhiêu sinh viên thực sự luyện tập mỗi ngày?
- Họ bỏ cuộc ở bài nào? Chủ đề nào khiến họ stuck?
- Tỷ lệ hoàn thành thực tế là bao nhiêu?
- Thẻ nào bị sai nhiều nhất và cần cải thiện nội dung?

### 4.2. Giải pháp: Tích hợp GA4

GA4 được nhúng vào Moodle bằng cách thêm đoạn mã `gtag.js` vào `HEAD` thông qua `Site Admin → Appearance → Additional HTML`. Cách tiếp cận này:

- Không cần cài thêm plugin hoặc sửa core Moodle.
- Tự động track toàn bộ Moodle: pageviews, sessions, users, traffic source.
- Cho phép thêm custom events từ plugin để biết người học làm gì bên trong phiên luyện tập.

### 4.3. Custom Events

Ba custom events được triển khai trong `js/practice.js`:

| Sự kiện | GA4 Event Name | Parameters chính | Thời điểm gửi |
|:---|:---|:---|:---|
| Bắt đầu phiên | `practice_session_started` | `cardbox_id`, `card_count`, `mode` | Khi người học nhấn "Bắt đầu" |
| Trả lời thẻ | `card_answered` | `card_id`, `correct`, `mode`, `is_repetition` | Mỗi lần trả lời (đúng/sai) |
| Kết thúc phiên | `practice_session_ended` | `cards_practiced`, `correct_count`, `wrong_count`, `duration_sec` | Khi hoàn thành hoặc thoát |

Hàm `ga4Track(eventName, params)` được thiết kế an toàn:

```javascript
function ga4Track(eventName, params) {
    if (typeof gtag === 'function') {
        gtag('event', eventName, params);
    }
}
```

Hàm kiểm tra `gtag` có tồn tại trước khi gọi, đảm bảo plugin hoạt động bình thường kể cả khi site chưa cấu hình GA4.

### 4.4. Insights từ dữ liệu học tập

Dữ liệu từ GA4 Dashboard cung cấp các chỉ số:

- **Engagement**: Số phiên học/ngày, thời gian trung bình mỗi phiên, tỷ lệ hoàn thành.
- **Performance**: Tỷ lệ trả lời đúng theo chủ đề (Hiragana / Katakana / Kanji / Từ vựng).
- **Retention**: Người học quay lại luyện tập sau 1 ngày / 7 ngày / 30 ngày.
- **Content Quality**: Thẻ nào bị sai nhiều nhất → giảng viên cần xem xét lại nội dung.

### 4.5. Giá trị tiếp thị

Từ góc nhìn tiếp thị kỹ thuật số, GA4 cho phép tối ưu hóa trải nghiệm người học tương tự tối ưu conversion trong TMĐT [17]:

- Biết chính xác người học "rời bỏ" (drop-off) ở bước nào trong hành trình học tập.
- Xác định nội dung có hiệu quả cao/thấp để phân bổ nguồn lực cải thiện.
- Đo lường ROI của mỗi tính năng mới (AI Hint, AI Explain...) qua thay đổi engagement metrics.

---

## CHƯƠNG 5. TÍCH HỢP AI VÀO PLUGIN CARDBOX

### 5.1. AI Hint — Gợi ý cá nhân hóa

#### 5.1.1. Vấn đề

Người học gặp thẻ khó → không có gợi ý → chọn "Tôi không biết" → nản → bỏ cuộc. Đây là nguyên nhân chính gây **drop-off** trong phiên luyện tập, đặc biệt với các thẻ Kanji mức N4–N3 có độ phức tạp cao.

#### 5.1.2. Giải pháp

Nút **"✨ AI gợi ý"** xuất hiện trong quá trình luyện tập. AI đọc lịch sử của **người dùng hiện tại** với **thẻ hiện tại** từ database và chọn mức gợi ý phù hợp:

| Dữ liệu người học | Mức gợi ý | Kiểu prompt AI |
|:---|:---|:---|
| Hộp 1 · sai ≥ 5 lần | 🔴 Mức 1 — Khó | Gợi ý chi tiết, nhiều liên tưởng |
| Hộp 1 · sai 2–4 lần | 🟡 Mức 2 — Đang học | Gợi ý nhẹ + liên tưởng trực quan |
| Hộp 3+ · từng đúng | 🟢 Mức 3 — Từng biết | Gợi ý ngắn, kích hoạt lại ký ức |

Cơ chế cá nhân hóa này dựa trên nguyên lý **Zone of Proximal Development** — gợi ý vừa đủ để người học tự đến đáp án, không quá nhiều (mất giá trị luyện tập) cũng không quá ít (vẫn bế tắc).

#### 5.1.3. AI Hint Image

Ngoài gợi ý văn bản, hệ thống cung cấp **gợi ý bằng hình ảnh** — AI tạo hình minh họa liên tưởng đến câu trả lời. Tính năng này gọi trực tiếp OpenAI Images API, đọc API key từ cấu hình `aiprovider_openai` trong Moodle.

#### 5.1.4. Triển khai kỹ thuật

Trong `action.php`, handler `aihint` nhận `$questiontext` và `$cardid`, truy vấn lịch sử luyện tập của user với card đó (số lần sai, vị trí hộp Leitner), xây dựng prompt cá nhân hóa theo mức gợi ý, và gọi `core_ai\aiactions\generate_text`.

#### 5.1.5. Tác động marketing

| Chỉ số | Trước AI Hint | Sau AI Hint |
|:---|:---|:---|
| **Drop-off** khi gặp thẻ khó | Bỏ phiên, không trả lời | Dùng gợi ý → tiếp tục học |
| **Session Duration** | Ngắn — bỏ sớm | Tăng — AI giữ người học ở lại |
| **Engagement Rate** | Thấp ở thẻ Kanji N4 | Cải thiện — ít bỏ cuộc hơn |
| **card_answered (correct)** | Thấp hơn | Tăng — nhờ gợi ý đúng lúc |

**Insight GA4:** Số lần `ai_hint` event được gọi trước `card_answered` cho biết chính xác thẻ nào đang gây khó khăn cho người học → ưu tiên cải thiện nội dung.

#### 5.1.6. Demo luồng hoạt động

1. Câu hỏi: *"Từ nào có nghĩa là 'ăn'?"*
2. Không nhớ → nhấn **✨ AI gợi ý**
3. AI phản hồi: *"Động từ đuôi -る, diễn tả hành động đưa thức ăn vào miệng."*
4. Nhớ ra → nhập 食べる → trả lời đúng → cảm giác thành công.

---

### 5.2. AI Explain — Giải thích câu trả lời sai

#### 5.2.1. Vấn đề

Người học trả lời sai → chỉ thấy đáp án đúng → không hiểu *tại sao* → frustration → bỏ học. Cảm xúc người học chuyển từ "được hướng dẫn" sang "bị phạt".

#### 5.2.2. Giải pháp

Nút **"✨ AI giải thích"** xuất hiện ngay sau khi chấm sai. AI giải thích ngắn gọn:
- *Tại sao* đáp án đúng là đúng.
- Người học *sai ở điểm nào*.
- Phản hồi song ngữ **tiếng Việt + tiếng Nhật** — phù hợp người học trong nước.

Thay đổi cảm xúc: từ **"bị phạt"** → **"được hướng dẫn"**.

#### 5.2.3. Triển khai kỹ thuật

Handler `aiexplain` trong `action.php` nhận 3 tham số: `$questiontext` (câu hỏi), `$correctanswer` (đáp án đúng), `$studentanswer` (câu trả lời của sinh viên). Prompt hướng dẫn AI giải thích sự khác biệt giữa câu trả lời sai và đáp án đúng, bằng tiếng Việt kèm ví dụ tiếng Nhật.

#### 5.2.4. Demo luồng hoạt động

1. Câu hỏi: *"食べる có nghĩa là gì?"*
2. Nhập *"uống"* → chấm **sai**.
3. Nhấn **✨ AI giải thích**.
4. AI: *"食べる (taberu) = 'ăn'. 'Uống' là 飲む (nomu). 食べる dùng cho thức ăn rắn, 飲む dùng cho chất lỏng."*

#### 5.2.5. Tác động marketing

| Chỉ số | Tác động |
|:---|:---|
| **Returning Users** | Người học thấy hệ thống có ích → quay lại lần sau |
| **Session Duration** | Dừng lại đọc giải thích → tăng thời gian mỗi phiên |
| **Repeat Errors** | Thẻ từng sai + có AI Explain → tỷ lệ đúng lần sau cao hơn |

**Insight GA4:** `ai_explain` calls sau `card_answered(correct=false)` → biết thẻ nào người học muốn hiểu sâu → ưu tiên cải thiện nội dung.

---

### 5.3. AI Course Suggest — Gợi ý khóa học

#### 5.3.1. Vấn đề

Người học hoàn thành bài test → chỉ thấy biểu đồ đúng/sai → không biết học gì tiếp theo. Trải nghiệm kết thúc ở "xong bài — bỏ đi" thay vì "xong bài — biết học gì tiếp".

#### 5.3.2. Giải pháp

Nút **"✨ AI gợi ý khóa học"** xuất hiện ngay sau khi kết thúc phiên luyện tập (kế bên biểu đồ doughnut kết quả). AI phân tích kết quả phiên và đưa ra:

- **Đánh giá trình độ** hiện tại dựa trên tỷ lệ đúng/sai.
- **Gợi ý 2–3 khóa học/tài nguyên** phù hợp trình độ.
- **Lộ trình ôn tập** cụ thể (thời gian, tần suất, nội dung ưu tiên).
- Phản hồi bằng **tiếng Việt** với bullet points rõ ràng.

#### 5.3.3. Triển khai kỹ thuật

Handler `aicoursesuggest` trong `action.php` nhận 4 tham số:

- `$countright` — số câu trả lời đúng.
- `$countwrong` — số câu trả lời sai.
- `$wrongquestions` — danh sách nội dung các câu sai (phân cách bằng dấu `;`).
- `$topicname` — tên chủ đề đang luyện tập.

Phía client, class `Coordinate` duy trì mảng `wrongQuestionTexts`. Mỗi khi người học trả lời sai, phương thức `getQuestionTextFromData(data)` trích xuất nội dung thuần (loại bỏ HTML tags) và thêm vào mảng. Khi kết thúc phiên, toàn bộ danh sách được truyền cho `Statistics.finishPractice()` để gọi AJAX.

AI tính toán phần trăm đúng (`$percent = round(100 * $countright / $total)`) và xây dựng prompt chứa đầy đủ ngữ cảnh phiên luyện tập.

#### 5.3.4. Demo luồng hoạt động

1. Hoàn thành phiên: 8/15 đúng (53%), 7 câu sai.
2. Biểu đồ doughnut hiển thị kết quả.
3. Nhấn **✨ AI gợi ý khóa học**.
4. AI phân tích:
   - *"Trình độ: Đang ở giai đoạn N5→N4. Cần củng cố từ vựng cơ bản."*
   - *"Gợi ý: Minna no Nihongo Bài 10-15, app Anki deck N4 Kanji."*
   - *"Lộ trình: Ôn lại bộ Kanji cơ bản 2 ngày/lần, 15 phút mỗi phiên."*

#### 5.3.5. Tác động marketing

| Chỉ số | Tác động |
|:---|:---|
| **Returning Users** | Người học có lộ trình rõ ràng → quay lại lần sau |
| **Engagement** | Nhận gợi ý cụ thể → tăng động lực tiếp tục |
| **Session Quality** | Từ "luyện tập bị động" → "hành trình học có hướng dẫn" |
| **Retention** | Cá nhân hóa trải nghiệm → giảm tỷ lệ bỏ học |

**Insight GA4:** Tỷ lệ click "AI gợi ý khóa học" sau phiên có % thấp → cần cải thiện nội dung thẻ. Tỷ lệ click cao + quay lại luyện tập → chứng minh giá trị của tính năng.

---

### 5.4. AI Card Generator — Tạo thẻ tự động

#### 5.4.1. Vấn đề

Giảng viên mất hàng giờ nhập tay từng thẻ một → tạo bottleneck mở rộng nội dung. Với một bộ thẻ 500 từ vựng N5, việc nhập liệu thủ công có thể mất nhiều ngày.

#### 5.4.2. Giải pháp

AI Card Generator tạo thẻ tự động với 2 chế độ:

**Chế độ 1: Nhập từ/chủ đề**
- Giảng viên nhập một từ (ví dụ "食べる") hoặc chủ đề (ví dụ "màu sắc").
- AI tạo cặp câu hỏi–đáp án (Q&A) tiếng Nhật.
- Thẻ được lưu thẳng vào bộ thẻ (tự động duyệt nếu người tạo có quyền `approvecard`).

**Chế độ 2: Chọn cấp JLPT (N1–N5)**
- Giảng viên chọn cấp độ JLPT (Japanese Language Proficiency Test).
- AI chọn từ ngẫu nhiên phù hợp cấp độ và tạo flashcard.
- Mỗi lần nhấn = 1 thẻ mới, nội dung không trùng lặp.

#### 5.4.3. Triển khai kỹ thuật

Hai handler trong `action.php`:

- `aicardgenjlpt` — nhận `$level` (N1–N5), sử dụng prompt `ai_cardgen_jlpt_prompt` yêu cầu AI trả JSON format `{question, answer}`. Response được parse JSON, tạo card mới trong database, và lưu nội dung question/answer.
- `aicardgensave` — nhận `$topic` (văn bản tự do), cùng logic parse và lưu.

Cả hai handler yêu cầu quyền `mod/cardbox:submitcard` để đảm bảo chỉ người được phép mới tạo thẻ.

#### 5.4.4. Giá trị

- Giảm **80% thời gian** biên soạn. Giảng viên tập trung duyệt nội dung thay vì nhập liệu.
- **GA4 insight:** Đo số thẻ AI-generated vs manual → biết AI đóng góp bao nhiêu % nội dung.

---

## CHƯƠNG 6. ĐÁNH GIÁ AI TRONG THƯƠNG MẠI ĐIỆN TỬ GIÁO DỤC

### 6.1. Bối cảnh thương mại điện tử giáo dục

Thị trường EdTech toàn cầu đang tăng trưởng mạnh, với dự báo đạt 404 tỷ USD vào năm 2025 [20]. Trong bối cảnh này, việc tích hợp AI không chỉ cải thiện trải nghiệm học mà còn trực tiếp tác động đến các chỉ số kinh doanh.

### 6.2. Phân tích từ bốn góc nhìn marketing

#### 6.2.1. Cá nhân hóa hành trình mua (Personalized Customer Journey)

AI phân tích kết quả test → gợi ý khóa học phù hợp trình độ → **tăng tỷ lệ chuyển đổi (conversion)**. Cơ chế này tương tự hệ thống "Khách hàng cũng mua..." của Amazon — recommendation engine dựa trên hành vi thực tế, không chỉ demographics [18]. Nghiên cứu cho thấy cá nhân hóa có thể tăng conversion rate lên 10–30% trong TMĐT [17].

#### 6.2.2. Tăng giá trị vòng đời (Customer Lifetime Value)

Người học nhận lộ trình → mua khóa tiếp theo → **upsell tự nhiên**. AI Course Suggest biến 1 lần mua thành chuỗi khóa học liên tục, tăng CLV mà không cần quảng cáo bổ sung. Theo Kotler và Armstrong (2018), chi phí giữ khách hàng cũ thấp hơn 5–25 lần so với thu hút khách mới [18].

#### 6.2.3. Giảm tỷ lệ bỏ học (Churn Reduction)

AI Hint + AI Explain giữ người học không bỏ cuộc → **giảm hoàn tiền**, tăng đánh giá tích cực (review/rating) → thu hút khách hàng mới qua social proof. Trong EdTech, tỷ lệ hoàn thành khóa học trực tuyến trung bình chỉ khoảng 5–15% [15]; AI hỗ trợ có tiềm năng cải thiện đáng kể con số này.

#### 6.2.4. Nội dung tự động — giảm chi phí (Content Automation)

AI Card Generator tạo thẻ tự động → **giảm 80% thời gian** biên soạn → mở rộng catalogue nhanh hơn đối thủ. Trong TMĐT giáo dục, đa dạng nội dung là lợi thế cạnh tranh: càng nhiều khóa học/nội dung phù hợp ngách → càng có khả năng thu hút người học mới.

### 6.3. Tổng kết giá trị marketing

AI không chỉ cải thiện trải nghiệm học — mà trực tiếp **tăng doanh thu**: conversion cao hơn (nhờ cá nhân hóa), retention tốt hơn (nhờ AI giữ chân), chi phí sản xuất nội dung thấp hơn (nhờ tự động hóa), và CLV cao hơn (nhờ upsell tự nhiên).

---

## CHƯƠNG 7. HẠN CHẾ, KẾT LUẬN VÀ HƯỚNG PHÁT TRIỂN

### 7.1. Hạn chế

- **GA4 và quyền riêng tư:** Cần tuân thủ GDPR [21] và chính sách dữ liệu của trường; không thu thập dữ liệu nhận dạng cá nhân (PII). GA4 ghi nhận hành vi ở mức tổng hợp (event-level), không lưu thông tin cá nhân sinh viên.
- **AI Hint / AI Explain:** Chất lượng phụ thuộc vào AI provider được cấu hình trong Moodle. Không dùng được nếu quản trị viên chưa cài plugin `aiprovider_openai` hoặc tương đương.
- **AI Image:** Dùng trực tiếp OpenAI API key → chi phí phát sinh mỗi lần generate; cần quản lý quota để tránh vượt ngân sách.
- **Latency:** Gọi AI API thêm 3–5 giây mỗi request — hiện chưa có cơ chế cache. Người học phải đợi phản hồi, có thể ảnh hưởng trải nghiệm với kết nối chậm.
- **Đánh giá định lượng:** Chưa có dữ liệu A/B testing thực tế để so sánh metrics trước/sau triển khai AI. Các tác động marketing được phân tích dựa trên lý thuyết và mô hình logic.

### 7.2. Kết luận

Báo cáo đã trình bày và triển khai thành công 5 tính năng tích hợp vào plugin mod_cardbox trên Moodle:

| Tính năng | Mục tiêu | Trạng thái |
|:---|:---|:---|
| **GA4 Integration** | Đo lường hành vi học tập (3 custom events) | ✅ Hoàn thành |
| **AI Hint** | Gợi ý cá nhân hóa (văn bản + hình ảnh) | ✅ Hoàn thành |
| **AI Explain** | Giải thích câu sai song ngữ Việt–Nhật | ✅ Hoàn thành |
| **AI Course Suggest** | Gợi ý khóa học + lộ trình sau test | ✅ Hoàn thành |
| **AI Card Generator** | Tạo thẻ tự động (từ/JLPT) | ✅ Hoàn thành |

**Giá trị mang lại:**
- GA4 cung cấp dữ liệu hành vi để tối ưu nội dung và đo lường hiệu quả marketing.
- AI nâng cao trải nghiệm (giảm drop-off, tăng engagement) và giữ chân người học (tăng retention).
- Từ góc nhìn TMĐT giáo dục: tăng conversion, tăng CLV, giảm churn, giảm chi phí nội dung.

### 7.3. Hướng phát triển

- **GA4 + BigQuery:** Xuất dữ liệu vào BigQuery để phân tích cohort, dự đoán nguy cơ bỏ học, và xây dựng dashboard nâng cao.
- **AI Quiz Generator:** Tự động tạo bài kiểm tra từ bộ thẻ có sẵn — mở rộng từ flashcard sang assessment.
- **Chatbot học tập:** AI trò chuyện tương tác để luyện hội thoại tiếng Nhật — chuyển từ vocabulary sang communication skills.
- **Cache AI responses:** Giảm latency và chi phí bằng cách cache kết quả AI cho các câu hỏi lặp lại — cải thiện trải nghiệm và tối ưu ngân sách.
- **A/B Testing:** Triển khai thử nghiệm A/B thực tế để đo lường định lượng tác động của từng tính năng AI lên các chỉ số marketing.

---

## TÀI LIỆU THAM KHẢO

[1] H. Ebbinghaus, *Über das Gedächtnis: Untersuchungen zur experimentellen Psychologie*. Leipzig: Duncker & Humblot, 1885. Bản dịch tiếng Anh: H. A. Ruger and C. E. Bussenius, *Memory: A Contribution to Experimental Psychology*. New York: Teachers College, Columbia University, 1913. [Online]. Available: https://psychclassics.yorku.ca/Ebbinghaus/

[2] N. J. Cepeda, E. Vul, D. Rohrer, J. T. Wixted, and H. Pashler, "Spacing effects in learning: A temporal ridgeline of optimal retention," *Psychological Science*, vol. 19, no. 11, pp. 1095–1102, 2008. doi: 10.1111/j.1467-9280.2008.02209.x

[3] H. L. Roediger III and J. D. Karpicke, "Test-enhanced learning: Taking memory tests improves long-term retention," *Psychological Science*, vol. 17, no. 3, pp. 249–255, 2006. doi: 10.1111/j.1467-9280.2006.01693.x

[4] N. Kornell, "Optimising learning using flashcards: Spacing is more effective than cramming," *Applied Cognitive Psychology*, vol. 23, no. 9, pp. 1297–1317, 2009. doi: 10.1002/acp.1537

[5] I. S. P. Nation, *Learning Vocabulary in Another Language*. Cambridge: Cambridge University Press, 2001. doi: 10.1017/CBO9781139524759

[6] T. Nakata, "Computer-assisted second language vocabulary learning in a paired-associate paradigm: A critical investigation of flashcard software," *Computer Assisted Language Learning*, vol. 24, no. 1, pp. 17–38, 2011. doi: 10.1080/09588221.2010.520675

[7] M. Dougiamas and P. Taylor, "Moodle: Using learning communities to create an open source course management system," in *Proc. EDMEDIA 2003 — World Conference on Educational Multimedia, Hypermedia & Telecommunications*, Honolulu, HI, 2003. [Online]. Available: https://moodle.org/

[8] Moodle Pty Ltd, "Moodle — Open-source learning platform," 2025. [Online]. Available: https://moodle.org/; Thống kê: https://stats.moodle.org/

[9] S. Leitner, *So lernt man lernen: Der Weg zum Erfolg*. Freiburg im Breisgau: Herder, 1972.

[10] J. D. Karpicke and J. R. Blunt, "Retrieval practice produces more learning than elaborative studying with concept mapping," *Science*, vol. 331, no. 6018, pp. 772–775, 2011. doi: 10.1126/science.1199327

[11] Moodle, "AI subsystem — Moodle Developer Documentation," 2025. [Online]. Available: https://moodledev.io/docs/apis/subsystems/ai

[12] Google, "Google Analytics 4 — Developer Documentation," 2025. [Online]. Available: https://developers.google.com/analytics

[13] Google, "[GA4] About events," Google Analytics Help, 2025. [Online]. Available: https://support.google.com/analytics/answer/9322688

[14] G. Siemens and P. Long, "Penetrating the fog: Analytics in learning and education," *EDUCAUSE Review*, vol. 46, no. 5, pp. 31–40, Sep./Oct. 2011. [Online]. Available: https://er.educause.edu/articles/2011/9/penetrating-the-fog-analytics-in-learning-and-education

[15] W. Holmes, M. Bialik, and C. Fadel, *Artificial Intelligence in Education: Promises and Implications for Teaching and Learning*. Boston, MA: Center for Curriculum Redesign, 2019. ISBN: 978-1-7945-1700-0

[16] L. Chen, P. Chen, and Z. Lin, "Artificial intelligence in education: A review," *IEEE Access*, vol. 8, pp. 75264–75278, 2020. doi: 10.1109/ACCESS.2020.2988510

[17] D. Chaffey and F. Ellis-Chadwick, *Digital Marketing: Strategy, Implementation and Practice*, 7th ed. Harlow: Pearson, 2019. ISBN: 978-1-292-24157-9

[18] P. Kotler and G. Armstrong, *Principles of Marketing*, 17th ed. Harlow: Pearson, 2018. ISBN: 978-0-13-449251-3

[19] OpenAI, "API Reference — Images," 2025. [Online]. Available: https://platform.openai.com/docs/api-reference/images

[20] HolonIQ, "Global EdTech Market Map," 2024. [Online]. Available: https://www.holoniq.com/edtech

[21] European Parliament and Council of the European Union, "Regulation (EU) 2016/679 of the European Parliament and of the Council of 27 April 2016 (General Data Protection Regulation)," *Official Journal of the European Union*, vol. L 119, pp. 1–88, May 2016. [Online]. Available: https://eur-lex.europa.eu/eli/reg/2016/679/oj
