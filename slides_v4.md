---
marp: true
theme: blue-academic
paginate: true
title: "Tích hợp Google Analytics và AI vào ứng dụng web giảng dạy tiếng Nhật"
description: "Tích hợp GA4 và AI vào plugin Cardbox (Moodle) dạy tiếng Nhật"
---

<!-- _class: title -->

<div class="title-logo">
  <img src="images/logoCTU.png" alt="CTU Logo" />
</div>

<div class="title-main">
  <div class="t1">TÍCH HỢP GOOGLE ANALYTICS VÀ AI</div>
  <div class="t2">VÀO ỨNG DỤNG WEB GIẢNG DẠY TIẾNG NHẬT</div>
  <div class="title-underline"></div>
</div>

<div class="title-meta">
  <div>
    <div class="label">Giảng viên hướng dẫn:</div>
    <div class="value">PGS.TS. Nguyễn Thái Nghe</div>
  </div>
  <div style="text-align:right;">
    <div class="label">Thành viên thực hiện:</div>
    <div class="value">Nguyễn Thái Duy (M2525017)</div>
    <div class="value">Đỗ Thị Cẩm Hằng (M2525021)</div>
  </div>
</div>

<div class="title-bar">
  <div>CT573HT - Tiếp thị và kinh doanh kỹ thuật số</div>
  <div>College of ICT, Can Tho University</div>
</div>

---

## Mục lục

1. Phân tích thị trường & Đặt vấn đề
2. Nền tảng: Moodle & Plugin Cardbox
3. Hành trình người học & Conversion Funnel
4. Tích hợp Google Analytics 4 (GA4)
5. Tích hợp AI — (1) AI Hint: Gợi ý câu hỏi
6. Tích hợp AI — (2) AI Explain: Giải thích câu trả lời sai
7. Tích hợp AI — (3) Nhập thẻ hàng loạt (Mass Import CSV)
8. Kiến trúc & Triển khai
9. Kết quả & KPI Marketing
10. Hạn chế & Hướng phát triển
11. Q&A

---

## Đặt vấn đề

**Học flashcard — nhưng vẫn thiếu điều gì đó**

- Gặp thẻ khó → không có gợi ý, không biết hỏi ai → bỏ cuộc
- Lật thẻ xong → không hiểu *tại sao* đáp án lại như vậy
- Học một mình → không có phản hồi, không có động lực tiếp tục
- Bộ thẻ lớn → giảng viên mất hàng giờ nhập tay từng thẻ một

**Và từ góc nhìn giảng viên:**

- Không biết nội dung nào giúp ích, nội dung nào nên bỏ
- Không có dữ liệu để cải thiện khóa học

**→ Flashcard hiệu quả — nhưng chưa đủ thú vị, chưa đủ thông minh**

---

## Nền tảng: Moodle & Plugin Cardbox

**Moodle LMS** — hệ thống quản lý học tập mã nguồn mở, phổ biến trong giáo dục đại học

**mod_cardbox** — plugin flashcard tích hợp sẵn trong Moodle

**Lợi ích của học bằng flashcard:**

| Lợi ích | Mô tả |
|:---|:---|
| **Spaced Repetition** | Ôn tập đúng lúc — tối ưu hoá trí nhớ dài hạn |
| **Active Recall** | Buộc não nhớ lại thay vì đọc thụ động |
| **Microlearning** | Học từng thẻ nhỏ — phù hợp học mọi lúc mọi nơi |
| **Tự đánh giá** | Người học biết ngay mình đang ở đâu |
| **Đa phương tiện** | Hỗ trợ văn bản, hình ảnh, âm thanh |

> Flashcard là phương pháp học ngoại ngữ được nghiên cứu và chứng minh hiệu quả — đặc biệt phù hợp với tiếng Nhật (Kanji, từ vựng, ngữ pháp).

---

<!-- _class: image-slide -->

## Giao diện Plugin Cardbox

<div style="display:flex; justify-content:center; align-items:center; height:88%">
  <img src="images/cardbox-overview.png" style="max-width:100%; max-height:100%; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15)">
</div>

---

## Hành trình người học & Conversion Funnel

Nhìn ứng dụng học tiếng Nhật như một **sản phẩm EdTech** — người học đi qua 5 giai đoạn:

| Giai đoạn | Hành động | GA4 đo lường |
|:---|:---|:---|
| **Awareness** | Truy cập site lần đầu | New Users, Traffic Source |
| **Activation** | Bắt đầu phiên luyện | `practice_session_started` |
| **Engagement** | Hoàn thành phiên, trả lời đúng | `card_answered`, Duration |
| **Retention** | Quay lại học ngày hôm sau | DAU, WAU, Returning Users |
| **Mastery** | Hoàn thành chủ đề | Cards mastered rate |

> **Insight**: AI cải thiện **Engagement** và **Retention** — hai giai đoạn mất người dùng nhiều nhất.

---

## Tổng quan hệ thống tích hợp

**Hai lớp tích hợp độc lập vào Moodle LMS:**

**Lớp 1 — GA4 (Đo lường hành vi)**
- `gtag.js` nhúng vào toàn site qua Additional HTML → HEAD
- Custom events từ `js/practice.js` → GA4 Dashboard

**Lớp 2 — AI Features (Nâng cao UX)**
- `action.php` xử lý AJAX từ `practice.js`
- Dùng Moodle `core_ai` subsystem → tương thích mọi LLM được cấu hình
- OpenAI Images API trực tiếp cho AI Image Hint

**Nguyên tắc: Non-invasive & Incremental**
- GA4: triển khai < 30 phút, không cần sửa code plugin
- AI: bật/tắt từng tính năng độc lập, không ảnh hưởng luồng học nếu AI ngoại tuyến

---

<!-- _class: content-slide -->

## Kiến trúc plugin trong Moodle

<div style="font-family:monospace; font-size:0.72em; line-height:1.6; padding:8px 0">

```
┌─────────────────────────── Moodle LMS ───────────────────────────────┐
│                                                                       │
│   Browser          mod_cardbox Plugin           Moodle Core          │
│  ┌────────┐       ┌──────────────────────┐    ┌──────────────────┐   │
│  │        │ AJAX  │ action.php           │    │ core_ai          │   │
│  │practice│──────▶│  - aihint            │───▶│ (generate_text)  │   │
│  │ .js    │       │  - aiexplain         │    └──────────────────┘   │
│  │        │       │  - aihint_image ─────│──────────────────────────────▶ OpenAI Images API
│  │ga4Track│──┐    └──────────────────────┘                           │
│  └────────┘  │     ┌─────────────────────┐                           │
│              │     │ Database (Moodle DB)│                           │
│              │     │  mdl_cardbox_cards  │                           │
│              │     └─────────────────────┘                           │
└──────────────┼───────────────────────────────────────────────────────┘
               │ gtag('event', ...)
               ▼
        ┌─────────────┐
        │  GA4 Server │
        │  Dashboard  │
        └─────────────┘
```

</div>

---

## Tích hợp Google Analytics 4 (GA4)

**Bạn đang vận hành một site Moodle — nhưng bạn không biết gì cả**
- Có bao nhiêu sinh viên thực sự luyện tập mỗi ngày?
- Họ bỏ cuộc ở bài nào? Chủ đề nào khiến họ stuck?
- Tỉ lệ hoàn thành thực tế là bao nhiêu?

→ *Moodle mặc định không ghi nhận hành vi học tập — GA4 lấp đầy khoảng trống đó.*

**GA4** — nền tảng phân tích thế hệ mới, **event-based**, privacy-first
- Nhúng `gtag.js` vào `HEAD` qua `Site Admin → Appearance → Additional HTML`
- Tự động track toàn Moodle: pageviews, sessions, users, traffic source
- Thêm custom events từ plugin để biết người học làm gì bên trong

---

<!-- _class: image-slide -->

## Cấu hình GA4 trong Moodle

<div style="display:flex; justify-content:center; align-items:center; height:95%">
  <img src="images/moodle-additional-html.png" style="width:100%; max-height:100%; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15)">
</div>

---

## GA4 — Custom Events cho Cardbox

Đã triển khai trong `js/practice.js`:

| Sự kiện | GA4 Event Name | Parameters chính |
|:---|:---|:---|
| Bắt đầu phiên | `practice_session_started` | `cardbox_id`, `card_count`, `mode` |
| Trả lời thẻ | `card_answered` | `card_id`, `correct`, `mode` |
| Kết thúc phiên | `practice_session_ended` | `cards_practiced`, `duration_sec` |

Hàm `ga4Track(eventName, params)` đặt ở **module scope** trong `practice.js` — cả `startPractice()` và object `Coordinate` đều gọi được. Hàm tự bỏ qua nếu `gtag` chưa được nạp, đảm bảo an toàn khi site chưa cài GA4.

**Ba thời điểm gửi event:** khi người học bắt đầu phiên, mỗi lần trả lời thẻ (kèm `correct: true/false`), và khi kết thúc phiên (kèm `duration_sec`).

---

## GA4 — Insights từ dữ liệu học tập

Các chỉ số theo dõi trên GA4 Dashboard:

- **Engagement**: Số phiên học/ngày, thời gian trung bình mỗi phiên, tỷ lệ hoàn thành
- **Performance**: Tỷ lệ trả lời đúng theo chủ đề (Hiragana / Katakana / Kanji / Từ vựng)
- **Retention**: Người học quay lại luyện tập sau 1 ngày / 7 ngày / 30 ngày
- **Content**: Thẻ nào bị sai nhiều nhất → giảng viên cần xem xét lại nội dung

> **Giá trị tiếp thị**: GA4 cho phép tối ưu hoá trải nghiệm người học như tối ưu conversion — biết chính xác người học "rời bỏ" ở bước nào trong hành trình học tập.

---

<!-- _class: image-slide -->

## GA4 Events Report — Dashboard

<div style="display:flex; justify-content:center; align-items:center; height:88%">
  <img src="images/ga4-events-report.png" style="max-width:100%; max-height:100%; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15)">
</div>

---

## Tích hợp AI — (1) AI Hint: Gợi ý câu hỏi

**Vấn đề:** Người học gặp thẻ khó → bỏ cuộc → thoát phiên học → **mất Engagement**

**Giải pháp:** Nút **" AI gợi ý"** — giúp người học vượt qua điểm tắc mà không bị tiết lộ đáp án

- Gợi ý **1–2 câu ngắn** giúp gợi nhớ, song ngữ Việt–Nhật
- **Gợi ý văn bản**: mô tả, liên tưởng ngữ nghĩa
- **Gợi ý hình ảnh**: sinh ảnh minh hoạ trực quan qua OpenAI Images API

> Người học không cần chọn "Tôi không biết" — họ có cơ hội nhớ lại và trả lời đúng.

---

## AI Hint — Tác động marketing

Đo lường qua GA4 events trước/sau khi triển khai AI Hint:

| Chỉ số | Trước AI Hint | Sau AI Hint |
|:---|:---|:---|
| **Drop-off** khi gặp thẻ khó | Bỏ phiên, không trả lời | Dùng gợi ý → tiếp tục học |
| **Session Duration** | Ngắn — bỏ sớm | Tăng — AI giữ người học ở lại |
| **Engagement Rate** | Thấp ở thẻ Kanji N4 | Cải thiện — ít bỏ cuộc hơn |
| **card_answered (correct)** | Thấp hơn | Tăng — nhờ gợi ý đúng lúc |

> **Insight:** Số lần AI Hint được gọi = danh sách ưu tiên những thẻ cần cải thiện nội dung.

---

## AI Hint — Trải nghiệm người học

**Không có AI Hint:** Gặp câu khó → chọn "Tôi không biết" → thẻ bị đánh dấu sai → nản → thoát

**Có AI Hint:**

1. Câu hỏi: *"Từ nào có nghĩa là 'ăn'?"*
2. Không nhớ → nhấn **AI gợi ý**
3. AI: *"Động từ đuôi -る, diễn tả hành động đưa thức ăn vào miệng."*
4. **Nhớ ra → nhập đáp án → trả lời đúng → cảm giác thành công**

> **Insight GA4:** Số lần AI Hint được gọi trước `card_answered` cho biết chính xác thẻ nào
> đang làm người học bỏ cuộc → **ưu tiên cải thiện nội dung những thẻ đó trước**.

---


<!-- _class: image-slide -->

## AI Hint — Gợi ý hình ảnh minh hoạ

<div style="display:flex; justify-content:center; align-items:center; height:88%">
  <img src="images/ai-hint-images.png" style="max-width:100%; max-height:100%; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15)">
</div>

---

<!-- _class: image-slide -->

## AI Hint — Giao diện gợi ý văn bản

<div style="display:flex; justify-content:center; align-items:center; height:88%">
  <img src="images/ai-hint.png" style="max-width:100%; max-height:100%; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15)">
</div>

---

## Tích hợp AI — (2) AI Explain: Giải thích câu trả lời sai

**Vấn đề:** Người học trả lời sai → chỉ thấy đáp án đúng → không hiểu *tại sao* → **frustration → bỏ học**

**Giải pháp:** Nút **" AI giải thích"** xuất hiện ngay sau khi chấm sai

- AI giải thích ngắn gọn *tại sao đáp án đúng là đúng* và *sai ở điểm nào*
- Phản hồi song ngữ **tiếng Việt + tiếng Nhật** — phù hợp người học trong nước

> Thay đổi cảm xúc: từ **"bị phạt"** → **"được hướng dẫn"**

---

## AI Explain — Tác động marketing

Người học hiểu lý do sai → nhớ lâu hơn → **quay lại học** → tăng Retention

| Chỉ số | Tác động |
|:---|:---|
| **Returning Users** | Người học thấy hệ thống có ích → quay lại lần sau |
| **Session Duration** | Dừng lại đọc giải thích → tăng thời gian mỗi phiên |
| **Repeat errors** | Thẻ từng sai + có AI Explain → tỷ lệ đúng lần sau cao hơn |

> **Insight GA4:** `ai_explain` calls sau `card_answered(correct=false)` → biết thẻ nào người học muốn hiểu sâu → ưu tiên cải thiện nội dung.

---

## AI Explain — Demo luồng hoạt động

**Luồng trong Autocheck mode:**

1. Câu hỏi: *"食べる có nghĩa là gì?"*
2. Nhập *"uống"* → chấm **sai**, hiện đáp án đúng: *"ăn"*
3. Nhấn ** AI giải thích**
4. AI: *"食べる (taberu) = 'ăn'. 'Uống' là 飲む (nomu). 食べる dùng cho thức ăn rắn, 飲む dùng cho chất lỏng."*

**Prompt** (trong `lang/en/cardbox.php`):
> *"Giải thích ngắn gọn (2-3 câu) tại sao đáp án đúng là đúng. Tiếng Việt trước, sau đó tiếng Nhật."*

**Kết nối GA4:** `ai_explain` calls sau `card_answered(correct=false)` → biết thẻ nào cần cải thiện nội dung

---

<!-- _class: image-slide -->

## AI Explain — Minh hoạ giao diện

<div style="display:flex; justify-content:center; align-items:center; height:88%">
  <img src="images/ai-explain.png" style="max-width:100%; max-height:100%; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15)">
</div>


---

## Kiến trúc & Triển khai

**Lớp 1 — GA4 (Đo lường hành vi):**
- Nhúng `gtag.js` vào `HEAD` qua Additional HTML — không sửa code plugin
- **Triển khai < 30 phút**, track toàn bộ hành vi ngay lập tức

**Lớp 2 — AI Features (Nâng cao UX):**
- `action.php` gọi `core_ai` subsystem khi người học yêu cầu
- Không ảnh hưởng luồng học nếu AI provider ngoại tuyến
- Bật/tắt từng tính năng độc lập (Hint / Explain / Image)

**Nguyên tắc: Non-invasive & Incremental**
> Tích hợp từng bước, đo lường từng bước — không thay thế toàn bộ hệ thống.


---

## Kết quả & KPI Marketing

### GA4 — Lần đầu đo được hành vi người học

| KPI | Kết quả | Insight |
|:---|:---|:---|
| Traffic Source | Đo được | Phần lớn đến từ link LMS nội bộ — cơ hội mở rộng kênh |
| Drop-off point | **Phát hiện** | Người học bỏ nhiều nhất ở **thẻ Kanji N4** (tỷ lệ sai > 70%) |
| Returning Users | Đo được lần đầu | Baseline để đánh giá hiệu quả AI về sau |
| Session Duration | Đo được | Cơ sở so sánh trước/sau khi tích hợp AI |

### AI — Tính năng đã triển khai

| Tính năng | Kết quả |
|:---|:---|
| **AI Hint (text)** | Người học nhận gợi ý không tiết lộ đáp án, song ngữ Việt-Nhật |
| **AI Hint (image)** | Sinh ảnh minh hoạ qua OpenAI Images API (gpt-image-1-mini) |
| **AI Explain** | Giải thích câu sai song ngữ Việt-Nhật, giảm frustration |
| **Mass Import CSV** | Nhập hàng loạt thẻ từ LLM-generated content, auto-approve |

---

## Hạn chế

- **GA4 & quyền riêng tư**: Cần tuân thủ GDPR/chính sách dữ liệu của trường; không thu thập dữ liệu nhận dạng cá nhân
- **AI Hint / AI Explain**: Chất lượng phụ thuộc vào AI provider được cấu hình trong Moodle; không dùng được nếu admin chưa cài plugin `aiprovider_openai` hoặc tương đương
- **AI Image**: Dùng trực tiếp OpenAI API key — chi phí phát sinh mỗi lần generate; cần quản lý quota
- **Latency**: Gọi AI API thêm 3-5 giây mỗi request — hiện chưa có cơ chế cache

---

## Hướng phát triển

- **AI Hint nâng cao**: Cá nhân hóa gợi ý theo lịch sử sai lầm của từng người học
- **AI Auto-check ngữ nghĩa**: Dùng AI thay thế exact match — chấp nhận `食べる` / `たべる` / `taberu` đều đúng
- **AI Card Generator (nội bộ)**: Tích hợp LLM trực tiếp vào giao diện plugin để giảng viên sinh thẻ ngay trong Moodle
- **GA4 + BigQuery**: Xuất dữ liệu vào BigQuery để phân tích cohort và dự đoán nguy cơ bỏ học

---

# Q&A

Cám ơn Thầy và các anh chị đã chú ý lắng nghe

