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
  <div class="t2">VÀO HỖ TRỢ GIẢNG DẠY TIẾNG NHẬT TRÊN NỀN TẢNG MOODLE</div>
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

1. Đặt vấn đề
2. Nền tảng: Moodle & Plugin Cardbox
3. Flashcard — Hiệu quả & So sánh
4. Kiến trúc hệ thống tích hợp
5. Tích hợp Google Analytics 4 (GA4)
6. Tích hợp AI — (1) AI Hint: Gợi ý câu hỏi
7. Tích hợp AI — (2) AI Explain: Giải thích câu trả lời sai
8. Hạn chế & Hướng phát triển
9. Q&A

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

## Flashcard — Hiệu quả được chứng minh

<table style="width:100%; border-collapse:separate; border-spacing:12px; font-size:0.82em; margin-top:16px">
<tr>
  <td style="background:#e8f0fe; border-radius:10px; border-left:5px solid #1a56db; padding:18px 20px; vertical-align:top; width:33%">
    <div style="font-size:2.4em; font-weight:900; color:#1a56db; line-height:1">200%</div>
    <div style="color:#333; margin-top:6px">ghi nhớ tốt hơn với <strong>Spaced Repetition</strong> so với đọc thụ động</div>
    <div style="color:#888; font-size:0.85em; margin-top:4px">Cepeda et al., 2008</div>
  </td>
  <td style="background:#e6f4ea; border-radius:10px; border-left:5px solid #137333; padding:18px 20px; vertical-align:top; width:33%">
    <div style="font-size:2.4em; font-weight:900; color:#137333; line-height:1">50%</div>
    <div style="color:#333; margin-top:6px">ít thời gian hơn để đạt <strong>2.000 từ vựng</strong> tiếng Nhật</div>
    <div style="color:#888; font-size:0.85em; margin-top:4px">Nation, 2001 — SRS vs Textbook</div>
  </td>
  <td style="background:#fce8e6; border-radius:10px; border-left:5px solid #c5221f; padding:18px 20px; vertical-align:top; width:34%">
    <div style="font-size:2.4em; font-weight:900; color:#c5221f; line-height:1">80%</div>
    <div style="color:#333; margin-top:6px">tỷ lệ nhớ lại sau <strong>1 tuần</strong> với flashcard vs ~20% học thụ động</div>
    <div style="color:#888; font-size:0.85em; margin-top:4px">Roediger & Karpicke, 2006</div>
  </td>
</tr>
</table>

---

## Flashcard vs. Học truyền thống

<table style="width:100%; border-collapse:separate; border-spacing:12px; font-size:0.82em; margin-top:16px">
<tr>
  <td style="background:#f4f4f4; border-radius:10px; padding:18px 20px; vertical-align:top; width:48%">
    <div style="font-weight:700; color:#555; font-size:1em; margin-bottom:10px">📚 Học truyền thống</div>
    <ul style="color:#666; margin:0; padding-left:18px; line-height:2">
      <li>Đọc − ghi lại − đọc lại</li>
      <li>Không có phản hồi tức thì</li>
      <li>Ôn tập đồng loạt, không cá nhân hoá</li>
      <li>Đường cong quên lãng dốc</li>
    </ul>
  </td>
  <td style="background:#e8f0fe; border-radius:10px; padding:18px 20px; vertical-align:top; width:48%">
    <div style="font-weight:700; color:#1a56db; font-size:1em; margin-bottom:10px">🃏 Flashcard + Spaced Repetition</div>
    <ul style="color:#333; margin:0; padding-left:18px; line-height:2">
      <li><strong>Active Recall</strong> — buộc não tự nhớ lại</li>
      <li>Phản hồi ngay lập tức (đúng/sai)</li>
      <li>Ôn đúng lúc — tối ưu trí nhớ dài hạn</li>
      <li>10–15 phút/ngày đủ tiến bộ rõ rệt</li>
    </ul>
  </td>
</tr>
</table>

---

## Tổng quan hệ thống tích hợp

Hai lớp tích hợp **độc lập**, **không xâm phạm** vào core Moodle:

| | Lớp 1 — GA4 | Lớp 2 — AI Features |
|:---|:---|:---|
| **Mục tiêu** | Đo lường hành vi học tập | Nâng cao trải nghiệm người học |
| **Cách hoạt động** | `gtag.js` nhúng vào HEAD site | `action.php` gọi `core_ai` subsystem |
| **Triển khai** | < 30 phút, không sửa code | Bật/tắt từng tính năng độc lập |

---

<!-- _class: content-slide -->

## Kiến trúc plugin trong Moodle

<table style="width:100%; border-collapse:separate; border-spacing:0; font-size:0.78em; margin-top:10px">
<tr>
  <td style="width:28%; background:#e8f0fe; border-radius:10px; border-top:4px solid #1a56db; padding:12px 14px; vertical-align:top">
    <strong style="color:#1a56db; font-size:1.05em">🖥 Browser</strong><br><br>
    <code>practice.js</code><br>
    <span style="color:#555">└ render thẻ flashcard</span><br>
    <span style="color:#555">└ nhận input người dùng</span><br><br>
    <code>ga4Track()</code><br>
    <span style="color:#555">└ gửi custom events</span>
  </td>
  <td style="width:6%; text-align:center; vertical-align:middle; font-size:1.6em; color:#1a56db; padding:0 4px">
    ──<br>AJAX<br>──▶
  </td>
  <td style="width:28%; background:#fce8e6; border-radius:10px; border-top:4px solid #c5221f; padding:12px 14px; vertical-align:top">
    <strong style="color:#c5221f; font-size:1.05em">⚙️ mod_cardbox</strong><br><br>
    <code>action.php</code><br>
    <span style="color:#555">└ xử lý request</span><br>
    <span style="color:#555">└ chấm bài, gọi AI</span><br><br>
    <code>mdl_cardbox_*</code><br>
    <span style="color:#555">└ lưu thẻ & thống kê</span>
  </td>
  <td style="width:6%; text-align:center; vertical-align:middle; font-size:1.6em; color:#137333; padding:0 4px">──▶</td>
  <td style="width:32%; vertical-align:top">
    <div style="background:#e6f4ea; border-radius:8px; border-top:4px solid #137333; padding:10px 14px; margin-bottom:8px">
      <strong style="color:#137333">🤖 core_ai (Moodle)</strong><br>
      <span style="color:#555; font-size:0.95em">AI Hint · AI Explain · bất kỳ LLM nào</span>
    </div>
    <div style="background:#fff3e0; border-radius:8px; border-top:4px solid #e65100; padding:10px 14px; margin-bottom:8px">
      <strong style="color:#e65100">🖼 OpenAI Images API</strong><br>
      <span style="color:#555; font-size:0.95em">AI Image Hint (gợi ý bằng hình)</span>
    </div>
    <div style="background:#fef9e7; border-radius:8px; border-top:4px solid #f0ad4e; padding:10px 14px">
      <strong style="color:#d68910">📊 GA4 Server</strong><br>
      <span style="color:#555; font-size:0.95em">← gtag() từ Browser · phân tích hành vi</span>
    </div>
  </td>
</tr>
</table>

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

**Giải pháp:** Nút **"✨ AI gợi ý"** — gợi ý thông minh, **cá nhân hóa theo từng người học**

AI đọc lịch sử của **người dùng hiện tại** với **thẻ hiện tại** từ DB → chọn mức gợi ý phù hợp:

<table style="width:100%; border-collapse:separate; border-spacing:8px; font-size:0.8em; margin-top:6px">
<tr style="font-weight:700; text-align:center">
  <td style="background:#f0f0f0; border-radius:8px; padding:8px">Dữ liệu người học</td>
  <td style="background:#f0f0f0; border-radius:8px; padding:8px">→</td>
  <td style="background:#f0f0f0; border-radius:8px; padding:8px">Mức gợi ý</td>
  <td style="background:#f0f0f0; border-radius:8px; padding:8px">Kiểu prompt AI</td>
</tr>
<tr>
  <td style="background:#fce8e6; border-radius:8px; padding:8px; color:#c5221f">Hộp 1 · sai ≥ 5 lần</td>
  <td style="text-align:center">→</td>
  <td style="background:#fce8e6; border-radius:8px; padding:8px; font-weight:700; color:#c5221f">🔴 Mức 1 — Khó</td>
  <td style="color:#555; padding:8px">Gợi ý chi tiết, nhiều liên tưởng</td>
</tr>
<tr>
  <td style="background:#fff8e1; border-radius:8px; padding:8px; color:#b45309">Hộp 1 · sai 2–4 lần</td>
  <td style="text-align:center">→</td>
  <td style="background:#fff8e1; border-radius:8px; padding:8px; font-weight:700; color:#b45309">🟡 Mức 2 — Đang học</td>
  <td style="color:#555; padding:8px">Gợi ý nhẹ + liên tưởng trực quan</td>
</tr>
<tr>
  <td style="background:#e6f4ea; border-radius:8px; padding:8px; color:#137333">Hộp 3+ · từng đúng</td>
  <td style="text-align:center">→</td>
  <td style="background:#e6f4ea; border-radius:8px; padding:8px; font-weight:700; color:#137333">🟢 Mức 3 — Từng biết</td>
  <td style="color:#555; padding:8px">Gợi ý ngắn, kích hoạt lại ký ức</td>
</tr>
</table>

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

## Hạn chế

- **GA4 & quyền riêng tư**: Cần tuân thủ GDPR/chính sách dữ liệu của trường; không thu thập dữ liệu nhận dạng cá nhân
- **AI Hint / AI Explain**: Chất lượng phụ thuộc vào AI provider được cấu hình trong Moodle; không dùng được nếu admin chưa cài plugin `aiprovider_openai` hoặc tương đương
- **AI Image**: Dùng trực tiếp OpenAI API key — chi phí phát sinh mỗi lần generate; cần quản lý quota
- **Latency**: Gọi AI API thêm 3-5 giây mỗi request — hiện chưa có cơ chế cache

---

## Hướng phát triển

- ~~**AI Hint cá nhân hóa**~~: ✅ **Đã triển khai** — gợi ý thay đổi theo lịch sử sai/đúng của từng thẻ
- ~~**AI Card Generator**~~: ✅ **Đã triển khai** — nhập từ/chủ đề hoặc chọn cấp JLPT (N1–N5), AI tự tạo thẻ và lưu ngay vào bộ thẻ
- **GA4 + BigQuery**: Xuất dữ liệu vào BigQuery để phân tích cohort và dự đoán nguy cơ bỏ học

---

# Q&A

Cám ơn Thầy và các anh chị đã chú ý lắng nghe

