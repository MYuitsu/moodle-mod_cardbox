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

## Phân tích thị trường & Đặt vấn đề

**Từ góc nhìn tiếp thị kỹ thuật số:**

- Thị trường học ngoại ngữ trực tuyến tăng trưởng mạnh — EdTech cần đo lường hành vi người dùng như bất kỳ sản phẩm số nào
- Câu hỏi marketing cốt lõi: *Người học đến từ đâu? Họ rời bỏ ở bước nào? Nội dung nào giữ chân hiệu quả nhất?*
- **Google Analytics 4** là công cụ tiêu chuẩn để trả lời những câu hỏi này — nhưng trong giáo dục, việc định nghĩa *event* và *conversion* đúng cách mang tính quyết định

**Vấn đề kỹ thuật song hành:**

- Ứng dụng flashcard tiếng Nhật (Moodle + mod_cardbox) chưa khai thác dữ liệu hành vi học tập
- Hệ thống kiểm tra câu trả lời cứng không phù hợp với tiếng Nhật (Kanji / Hiragana / Romaji đều hợp lệ)

→ Đề xuất: tích hợp **GA4 ở cấp site** để đo lường + tích hợp **AI** vào plugin để nâng cao trải nghiệm

---

## Nền tảng: Moodle & Plugin Cardbox

**Moodle LMS** — hệ thống quản lý học tập mã nguồn mở, phổ biến trong giáo dục đại học

**mod_cardbox** — plugin flashcard dựa trên hệ thống **Leitner Box** (7 hộp):

| Hộp | Khoảng cách ôn tập |
|:---:|:---:|
| 0 (mới) | Luyện ngay |
| 1 | 1 ngày |
| 2 | 3 ngày |
| 3 | 7 ngày |
| 4 | 16 ngày |
| 5 | 34 ngày |
| 6 (thành thạo) | Kết thúc |

- Thẻ đúng → lên hộp; thẻ sai → về hộp 0
- Hỗ trợ nội dung: văn bản, hình ảnh, âm thanh

---

## Các chế độ luyện tập trong Cardbox

Ba chế độ hiện có:

**Flashcard** — lật thẻ, người học tự nhớ (không chấm điểm)

**Selfcheck** — người học tự đánh giá đúng/sai sau khi xem đáp án

**Autocheck** — hệ thống tự động so khớp chuỗi ký tự (exact/substring match)

> **Vấn đề của Autocheck**: So khớp cứng không chấp nhận câu trả lời đúng về mặt ngữ nghĩa.
> Ví dụ: "食べる" vs "たべる" vs "taberu" → cùng nghĩa nhưng bị tính sai.

---

## Hành trình người học & Conversion Funnel

Nhìn ứng dụng học tiếng Nhật như một **sản phẩm EdTech** — người học đi qua 5 giai đoạn:

| Giai đoạn | Hành động | GA4 đo lường | Vai trò AI |
|:---|:---|:---|:---|
| **Awareness** | Truy cập site lần đầu | New Users, Traffic Source | — |
| **Activation** | Bắt đầu phiên luyện đầu tiên | `practice_session_started` | — |
| **Engagement** | Hoàn thành phiên, trả lời đúng | `card_answered`, Session Duration | Semantic Check |
| **Retention** | Quay lại học ngày hôm sau | DAU, WAU, Returning Users | Gợi ý thích nghi |
| **Mastery** | Hoàn thành chủ đề, lên cấp độ | Cards mastered rate | AI Card Generator |

> **Insight chính**: AI cải thiện **Engagement** và **Retention** — hai giai đoạn mất người dùng nhiều nhất trong EdTech.

---

## Tổng quan hệ thống đề xuất

```
┌─────────────────────────────────────────────┐
│               Moodle LMS                    │
│  ┌──────────────────────────────────────┐   │
│  │         mod_cardbox Plugin           │   │
│  │  [Flashcard UI] ←→ [PHP Backend]     │   │
│  │         ↓ Events          ↓ REST API │   │
│  └─────────┬────────────────┬───────────┘   │
└────────────┼────────────────┼───────────────┘
             ↓                ↓
     ┌───────────────┐  ┌────────────────────┐
     │  GA4 (gtag.js)│  │  AI Service        │
     │  Learning     │  │  (FastAPI/Python)  │
     │  Analytics    │  │  - Semantic Check  │
     │  Dashboard    │  │  - Card Generator  │
     └───────────────┘  │  - Recommender     │
                        └────────────────────┘
```

---

## Tích hợp Google Analytics 4 (GA4)

### GA4 là gì?
- Nền tảng phân tích thế hệ mới của Google (ra mắt 2020, thay thế Universal Analytics từ 2023)
- **Event-based model**: mọi tương tác là một event (thay vì session-based)
- Hỗ trợ cross-platform (web + app), Privacy-first (không cookie bắt buộc)
- Tích hợp BigQuery miễn phí để phân tích chuyên sâu

### Tích hợp ở cấp **Moodle Site** — không phải plugin
- `gtag.js` được nhúng **toàn bộ trang Moodle** qua:
  - `Site administration → Appearance → Additional HTML → HEAD`
  - hoặc plugin `local_analytics` (quản lý tập trung, hỗ trợ nhiều Measurement ID)
- Mọi trang trong Moodle đều được track tự động: pageviews, sessions, users, traffic source

### Đề xuất mở rộng: Custom Events từ plugin
- GA4 mặc định chỉ track **pageview** — chưa biết người học làm gì bên trong plugin
- Đã thêm hàm `ga4Track()` vào `js/practice.js` của cardbox để gửi custom events
- `ga4Track()` tự động bỏ qua nếu GA4 chưa được cài trên site (safe, không lỗi)

---

## GA4 — Custom Events cho Cardbox

Đã triển khai trong `js/practice.js` — mapping sự kiện thực tế:

| Sự kiện | GA4 Event Name | Parameters chính |
|:---|:---|:---|
| Bắt đầu phiên luyện | `practice_session_started` | `cardbox_id`, `card_count`, `mode` |
| Trả lời thẻ (mọi mode) | `card_answered` | `card_id`, `correct`, `mode`, `is_repetition` |
| Kết thúc phiên | `practice_session_ended` | `cards_practiced`, `correct_count`, `wrong_count`, `duration_sec` |

```javascript
// js/practice.js — helper an toàn, bỏ qua nếu GA4 chưa cài
function ga4Track(eventName, params) {
    if (typeof gtag === 'function') {
        gtag('event', eventName, params);
    }
}

// Gọi khi bắt đầu phiên
ga4Track('practice_session_started', {
    cardbox_id: __cmid, card_count: __selection.length, mode: __mode
});

// Gọi mỗi lần trả lời thẻ (trong proceed())
ga4Track('card_answered', {
    cardbox_id: this.cmid, card_id: this.cardId,
    correct: iscorrect === 1, mode: this.mode
});
```

---

## GA4 — Insights từ dữ liệu học tập

Các chỉ số theo dõi trên GA4 Dashboard:

- **Engagement**: Số phiên học/ngày, thời gian trung bình mỗi phiên, tỷ lệ hoàn thành
- **Performance**: Tỷ lệ trả lời đúng theo chủ đề (Hiragana / Katakana / Kanji / Từ vựng)
- **Retention**: Người học quay lại luyện tập sau 1 ngày / 7 ngày / 30 ngày
- **Content**: Thẻ nào bị sai nhiều nhất → giảng viên cần xem xét lại nội dung

> **Giá trị tiếp thị**: GA4 cho phép tối ưu hoá trải nghiệm người học như tối ưu conversion — biết chính xác người học "rời bỏ" ở bước nào trong hành trình học tập.

---

## Tích hợp AI — (1) AI Hint: Gợi ý câu hỏi

### Đã triển khai trong plugin (action.php)
- Người học đang làm bài, không nhớ đáp án → nhấn nút **"AI gợi ý"**
- AI gợi ý **1–2 câu ngắn** giúp gợi nhớ **mà không tiết lộ đáp án**
- Hai chế độ song song:
  - **Gợi ý văn bản** (`ai_hint`): qua Moodle AI subsystem (`core_ai`) → tương thích mọi LLM được cấu hình
  - **Gợi ý hình ảnh** (`ai_hint_image`): gọi trực tiếp OpenAI Images API (gpt-image-1-mini) → sinh ảnh minh hoạ

### Tích hợp kỹ thuật
- `action.php` xử lý AJAX request từ `practice.js`
- Dùng `core_ai\aiactions\generate_text` của Moodle 4.5+ — trung lập với provider (OpenAI, Azure, v.v.)
- Ngôn ngữ phản hồi: **tiếng Việt trước, dịch sang tiếng Nhật**

### Giá trị với người học
- Không bỏ cuộc ngay khi gặp câu khó → **tăng Engagement**
- Nhận hỗ trợ đúng lúc mà không bị "tiết lộ đáp án" hoàn toàn → học sâu hơn

---

## AI Hint — Demo luồng hoạt động

**Luồng người học trong Autocheck mode:**

1. Người học thấy câu hỏi: *"Từ nào có nghĩa là 'ăn'?"*
2. Không nhớ → nhấn nút **🤖 AI gợi ý** (không nhấn "Tôi không biết")
3. AI trả lời: *"Đây là động từ đuôi -る, dùng để diễn tả hành động đưa thức ăn vào miệng. Tiếng Nhật: 口に入れる動作を表す動詞です。"*
4. Người học nhớ ra → nhập đáp án → hệ thống chấm bình thường

**Prompt thực tế** (định nghĩa trong `lang/en/cardbox.php`):
> *"Hãy đưa ra 1-2 câu gợi ý ngắn gọn giúp học sinh trả lời mà KHÔNG tiết lộ trực tiếp đáp án. Trả lời bằng tiếng Việt trước, sau đó dịch sang tiếng Nhật."*

**Kết nối với GA4:**

| GA4 signal | Insight |
|:---|:---|
| Số lần `ai_hint` được gọi trước `card_answered` | Thẻ nào khó nhất, người học cần hỗ trợ nhiều |
| Session Duration tăng sau khi có AI Hint | AI giữ người học ở lại thay vì bỏ cuộc |

---

## Tích hợp AI — (2) AI Explain: Giải thích câu trả lời sai

### Đã triển khai trong plugin (action.php)
- Người học trả lời **sai** → hệ thống hiện đáp án đúng + nút **"AI giải thích"**
- AI **giải thích tại sao đáp án đúng là đúng và học sinh sai điểm nào**
- Phản hồi song ngữ: **tiếng Việt + tiếng Nhật**

### Tích hợp kỹ thuật
- `practice.js` gửi AJAX đến `action.php?action=aiexplain`
- Payload gồm: `questiontext`, `correctanswer`, `studentanswer`
- Dùng Moodle `core_ai` subsystem — hoạt động với mọi AI provider được cấu hình trong Moodle

### Giá trị với người học
- Không chỉ biết *"sai"* mà hiểu *"sai ở đâu"* → học sâu hơn, nhớ lâu hơn
- Giảm frustration: không cảm thấy bị phạt mà được hướng dẫn
- **Tăng Retention**: người học thấy hệ thống có ích → quay lại lần sau

---

## AI Explain — Demo luồng hoạt động

**Luồng người học trong Autocheck mode:**

1. Câu hỏi: *"食べる có nghĩa là gì?"*
2. Người học nhập: *"uống"* → hệ thống chấm **sai**, hiện đáp án đúng: *"ăn"*
3. Người học nhấn **🤖 AI giải thích**
4. AI trả lời:
   > *"食べる (taberu) có nghĩa là 'ăn', không phải 'uống'. 'Uống' trong tiếng Nhật là 飲む (nomu). Hai từ này điều khiển các bộ phận khác nhau: 食べ- liên quan đến thức ăn rắn, 飲む- liên quan đến chất lỏng."*

**Prompt thực tế** (định nghĩa trong `lang/en/cardbox.php`):
> *"Học sinh trả lời sai. Hãy giải thích ngắn gọn (2-3 câu) tại sao đáp án đúng là đúng và đáp án của học sinh sai ở điểm nào. Trả lời bằng tiếng Việt trước, sau đó dịch sang tiếng Nhật."*

**Kết nối với GA4:**

| GA4 signal | Insight |
|:---|:---|
| Số lần `ai_explain` được gọi sau `card_answered (correct=false)` | Thẻ nào người học muốn hiểu sâu nhất |
| Tỷ lệ thẻ cùng loại trả lời sai lần sau | Xem AI Explain có giúp học nhớ hơn không |

---

## Tích hợp AI — (3) Nhập thẻ hàng loạt (Mass Import CSV)

### Đã triển khai trong plugin
- Giảng viên có thể **nhập hàng trăm thẻ cùng lúc** qua file CSV — không cần tạo từng thẻ
- Plugin cung cấp file mẫu: `example_singleans.csv` và `example_multians.csv`
- Hỗ trợ các cột: `ques`, `ans`, `ans2`, `ans3`, `qcontext`, `acontext`, `topic`, `acdisable`

### Quy trình Mass Import
1. Giảng viên chuẩn bị nội dung bằng LLM (ChatGPT, Claude, Gemini) → xuất ra CSV
2. Upload file CSV vào plugin qua giao diện Mass Import
3. Plugin xử lý: tạo topic tự động, validate dữ liệu, nhập vào DB
4. Thẻ được duyệt tự động (`approved = 1`) cho phép luyện tập ngay

### Giá trị — Kết hợp AI bên ngoài + Import nhanh
- Giảng viên dùng LLM **bên ngoài** để sinh nội dung → tốc độ tạo thẻ tăng đáng kể
- Không phụ thuộc vào bất kỳ API AI cụ thể nào — linh hoạt với mọi công cụ
- Kết hợp GA4: xem thẻ nào được học nhiều nhất → **ưu tiên tạo thêm nội dung tương tự**

---

## Kiến trúc & Triển khai

**Hai lớp tích hợp độc lập — triển khai từng bước, không rủi ro:**

**Lớp 1 — GA4 (Đo lường hành vi):**
- Nhúng một đoạn script vào `HEAD` của Moodle site qua Additional HTML
- Không cần sửa bất kỳ dòng code nào của plugin
- **Triển khai trong < 30 phút**, track toàn bộ hành vi ngay lập tức

**Lớp 2 — AI Service (Nâng cao UX):**
- Chạy song song với Moodle, plugin gọi khi cần xử lý
- Không ảnh hưởng luồng học tập hiện tại nếu AI Service ngoại tuyến
- Bật/tắt từng tính năng độc lập: Semantic Check / Card Generator / Adaptive

**Nguyên tắc: Non-invasive & Incremental**

> Không cần thay thế toàn bộ hệ thống — **tích hợp từng bước, đo lường từng bước**.
> Đây cũng là nguyên tắc của Growth Hacking: *thử nghiệm nhỏ → đo → mở rộng.*

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
- **Mass Import CSV**: Chỉ hỗ trợ nội dung văn bản; chưa hỗ trợ import thẻ có hình ảnh hoặc âm thanh
- **Latency**: Gọi AI API thêm 1–3 giây mỗi request — hiện chưa có cơ chế cache

---

## Hướng phát triển

- **AI Hint nâng cao**: Cá nhân hóa gợi ý theo lịch sử sai lầm của từng người học
- **AI Auto-check ngữ nghĩa**: Dùng AI thay thế exact match — chấp nhận `食べる` / `たべる` / `taberu` đều đúng
- **AI Card Generator (nội bộ)**: Tích hợp LLM trực tiếp vào giao diện plugin để giảng viên sinh thẻ ngay trong Moodle
- **GA4 + BigQuery**: Xuất dữ liệu vào BigQuery để phân tích cohort và dự đoán nguy cơ bỏ học
- **Mass Import hình ảnh**: Hỗ trợ nhập thẻ kèm file ảnh qua CSV + ZIP

---

# Q&A

Cám ơn Thầy và các anh chị đã chú ý lắng nghe

