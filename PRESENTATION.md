# 🎓 Moodle Card Box Plugin — Tích hợp AI trong Học tập

> **Nhóm phát triển:** Khoa Công nghệ Thông tin  
> **Nền tảng:** Moodle 4.5 LMS  
> **Ngày trình bày:** Tháng 3/2026

---

## 📌 Slide 1 — Giới thiệu Plugin Card Box

**Card Box** là một activity module cho Moodle, mô phỏng phương pháp **hệ thống Leitner** — một kỹ thuật học tập lặp lại cách quãng (spaced repetition) đã được khoa học chứng minh hiệu quả.

### Ý tưởng cốt lõi

```
┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐    ┌─────────┐
│  Deck 0 │───▶│  Deck 1 │───▶│  Deck 2 │───▶│  Deck 3 │───▶│  Deck 4 │───▶│  Deck 5 │
│  (Mới)  │    │  1 ngày │    │  3 ngày │    │  7 ngày │    │ 16 ngày │    │ 34 ngày │
└─────────┘    └─────────┘    └─────────┘    └─────────┘    └─────────┘    └─────────┘
                    ▲                                                           │
                    │              Trả lời sai → quay về Deck 1                 │
                    └───────────────────────────────────────────────────────────┘
```

- Giáo viên tạo bộ flashcard → Sinh viên luyện tập → Hệ thống tự động phân bổ lịch ôn tập
- Thẻ trả lời **đúng** → chuyển lên deck cao hơn (ôn ít hơn)
- Thẻ trả lời **sai** → quay về deck 1 (ôn lại ngay)

---

## 📌 Slide 2 — Các chức năng hiện tại

| Chức năng | Mô tả | Đối tượng |
|-----------|-------|-----------|
| **Tạo Flashcard** | Tạo thẻ với câu hỏi (text/hình/âm thanh) và tối đa 10 đáp án | GV, SV |
| **Import CSV** | Nhập hàng loạt thẻ từ file CSV với preview & validation | GV |
| **Luyện tập** | 3 chế độ: Tự kiểm tra, Tự động kiểm tra, Trắc nghiệm | SV |
| **Thống kê** | Biểu đồ tiến độ cá nhân, phân bổ deck, thống kê tuần | SV, GV |
| **Duyệt thẻ** | Phê duyệt/từ chối thẻ do sinh viên tạo | GV |
| **Quản lý chủ đề** | Tạo, đổi tên, xóa chủ đề cho thẻ | GV |
| **Thông báo** | Nhắc nhở luyện tập, thông báo khi thẻ được chỉnh sửa | Hệ thống |

### Các chế độ luyện tập

```
┌──────────────────────┐  ┌──────────────────────┐  ┌──────────────────────────┐
│   🔵 Tự kiểm tra     │  │   🟢 Tự động kiểm tra │  │   🟡 Trắc nghiệm         │
│                      │  │                      │  │                          │
│  Xem câu hỏi        │  │  Xem câu hỏi        │  │  Xem câu hỏi            │
│       ↓              │  │       ↓              │  │       ↓                  │
│  Tự trả lời         │  │  Nhập câu trả lời   │  │  Chọn 1 trong 6 đáp án  │
│       ↓              │  │       ↓              │  │       ↓                  │
│  Lật thẻ xem đáp án │  │  Hệ thống so khớp   │  │  Phản hồi đúng/sai      │
│       ↓              │  │  chính xác           │  │  ngay lập tức            │
│  Tự đánh giá đ/s    │  │       ↓              │  │                          │
│                      │  │  Hiển thị kết quả   │  │                          │
└──────────────────────┘  └──────────────────────┘  └──────────────────────────┘
```

---

## 📌 Slide 3 — Cấu trúc dữ liệu Flashcard

```
┌─────────────────────────────────────────────────────────┐
│                     FLASHCARD                           │
├─────────────────────────┬───────────────────────────────┤
│    MẶT TRƯỚC (Câu hỏi) │    MẶT SAU (Đáp án)          │
│                         │                               │
│  📝 Văn bản câu hỏi    │  📝 Đáp án chính (1-10)      │
│  🖼️ Hình ảnh minh họa  │  🖼️ Hình ảnh đáp án          │
│  🔊 File âm thanh       │  🔊 File âm thanh             │
│  💡 Gợi ý ngữ cảnh     │  💡 Giải thích ngữ cảnh       │
│                         │  📌 Gợi ý đáp án (SV đề xuất)│
└─────────────────────────┴───────────────────────────────┘
         │                           │
         │      Thuộc tính thẻ       │
         │  ┌─────────────────────┐  │
         └──│ Chủ đề (Topic)      │──┘
             │ Tác giả             │
             │ Trạng thái duyệt   │
             │ Số đáp án yêu cầu  │
             │ Phân biệt hoa/thường│
             └─────────────────────┘
```

---

## 📌 Slide 4 — Hạn chế của Plugin TRƯỚC khi có AI

### ❌ Vấn đề 1: Tạo thẻ thủ công tốn thời gian
- Giáo viên phải soạn **từng thẻ một** hoặc chuẩn bị file CSV
- Một môn học có thể cần **hàng trăm thẻ** → mất nhiều giờ

### ❌ Vấn đề 2: Trả lời sai nhưng không hiểu tại sao
- Khi sinh viên trả lời sai, hệ thống chỉ hiện **đáp án đúng**
- **Không có giải thích** → sinh viên chỉ ghi nhớ máy móc, không hiểu bản chất
- Hiệu quả học tập bị giới hạn

### ❌ Vấn đề 3: Không có hỗ trợ khi bí
- Khi gặp câu hỏi khó, sinh viên chỉ có 2 lựa chọn: **đoán bừa** hoặc **bỏ qua**
- Không có cơ chế **gợi ý từng bước** giúp sinh viên tự suy luận đến đáp án

### ❌ Vấn đề 4: Thiếu đa dạng hình thức gợi ý
- Sinh viên có phong cách học khác nhau (visual, textual, auditory)
- Hệ thống cũ **chỉ có text** → không hỗ trợ học qua hình ảnh

---

## 📌 Slide 5 — Tính cấp thiết của việc tích hợp AI

### 🌍 Bối cảnh giáo dục hiện đại

```
  2020                  2023                  2025                  2026
   │                     │                     │                     │
   ▼                     ▼                     ▼                     ▼
 COVID-19            ChatGPT ra đời       Moodle 4.5 tích hợp   Thời điểm
 → E-learning        → AI Generative      AI Subsystem chính    KHÔNG THỂ
   bùng nổ             bùng nổ            thức vào LMS           CHỜ THÊM
```

### 📊 Số liệu thuyết phục

| Chỉ số | Giá trị |
|--------|---------|
| Sinh viên mong muốn AI hỗ trợ học tập (UNESCO 2025) | **78%** |
| Cải thiện kết quả khi có AI giải thích lỗi sai (nghiên cứu meta-analysis) | **+23%** |
| Thời gian tiết kiệm cho GV khi dùng AI tạo nội dung | **60-70%** |
| LMS tích hợp AI tăng tỷ lệ engagement sinh viên | **+35%** |

### 🎯 Tại sao tích hợp AI vào Card Box là CẤP THIẾT?

1. **Moodle 4.5 đã sẵn sàng** — AI Subsystem có sẵn, chỉ cần gọi API
2. **Chi phí triển khai thấp** — Không cần xây dựng AI riêng, tận dụng provider có sẵn (OpenAI, Azure AI)
3. **Tác động cao** — Cải thiện trực tiếp trải nghiệm học tập của sinh viên
4. **Xu hướng không thể đảo ngược** — Các LMS đối thủ đã tích hợp AI, Moodle cần theo kịp
5. **Yêu cầu từ thực tiễn** — Sinh viên cần phản hồi thông minh, không chỉ đúng/sai

---

## 📌 Slide 6 — Giải pháp: 3 chức năng AI đã tích hợp

### 🤖 Chức năng 1: AI Gợi ý (Text)

```
┌────────────────────────────────────────────────────────────┐
│                    CÂU HỎI FLASHCARD                       │
│                                                            │
│  "Thủ đô của Nhật Bản là gì?"                             │
│                                                            │
│  ┌──────────────────┐                                      │
│  │ 🪄 AI gợi ý      │  ← Sinh viên bấm khi bí            │
│  └──────────────────┘                                      │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ 🪄 AI gợi ý                                          │  │
│  │                                                      │  │
│  │ Đây là thành phố lớn nhất Nhật Bản, nằm trên đảo    │  │
│  │ Honshu. Tên bắt đầu bằng chữ "T" và kết thúc       │  │
│  │ bằng "o". Thành phố này từng tổ chức Olympic 2020.  │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                            │
│  [A] Osaka    [B] Tokyo    [C] Kyoto    [D] Nagoya        │
└────────────────────────────────────────────────────────────┘
```

> **Điểm mấu chốt:** AI đưa ra gợi ý giúp sinh viên **tự suy luận** đến đáp án, KHÔNG tiết lộ trực tiếp.

---

### 🖼️ Chức năng 2: AI Gợi ý bằng hình

```
┌────────────────────────────────────────────────────────────┐
│                    CÂU HỎI FLASHCARD                       │
│                                                            │
│  "Công thức hóa học của nước là gì?"                       │
│                                                            │
│  ┌───────────────────────────┐                             │
│  │ 🖼️ AI gợi ý bằng hình    │  ← Học qua hình ảnh        │
│  └───────────────────────────┘                             │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ 🖼️ AI gợi ý bằng hình                                │  │
│  │                                                      │  │
│  │  ┌──────────────────────────────────────────────┐    │  │
│  │  │                                              │    │  │
│  │  │     🟡 ── 🔴 ── 🟡                            │    │  │
│  │  │     H      O      H                          │    │  │
│  │  │                                              │    │  │
│  │  │   (Hình minh họa phân tử do AI tạo)         │    │  │
│  │  └──────────────────────────────────────────────┘    │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                            │
│  [A] CO₂    [B] H₂O    [C] NaCl    [D] O₂                │
└────────────────────────────────────────────────────────────┘
```

> **Điểm mấu chốt:** Hỗ trợ **sinh viên học qua thị giác** (visual learners) — đặc biệt hiệu quả cho các môn khoa học, y học, địa lý.

---

### 💡 Chức năng 3: AI Giải thích khi sai

```
┌────────────────────────────────────────────────────────────┐
│                    KẾT QUẢ TRẢ LỜI                         │
│                                                            │
│  ✗ Osaka                    ← Sinh viên chọn sai          │
│  ✓ Tokyo                    ← Đáp án đúng                  │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  ✅ Tokyo                                             │  │
│  │  Tokyo là thủ đô và trung tâm chính trị của Nhật...  │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                            │
│  ┌──────────────────┐                                      │
│  │ 💡 AI Explain     │  ← Chỉ hiện khi trả lời SAI        │
│  └──────────────────┘                                      │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ 💡 AI Explain                                         │  │
│  │                                                      │  │
│  │ Tokyo là thủ đô của Nhật Bản từ năm 1868, khi       │  │
│  │ Thiên hoàng Minh Trị chuyển triều đình từ Kyoto.    │  │
│  │                                                      │  │
│  │ Osaka tuy là thành phố lớn thứ 3, nhưng chỉ là      │  │
│  │ trung tâm kinh tế vùng Kansai, không phải thủ đô.   │  │
│  │                                                      │  │
│  │ 💡 Mẹo nhớ: "Tokyo" = "Đông Kinh" (東京) = kinh đô  │  │
│  │ phía Đông.                                           │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                            │
│  [Tiếp tục ▶]                                              │
└────────────────────────────────────────────────────────────┘
```

> **Điểm mấu chốt:** Biến mỗi lần trả lời sai thành **cơ hội học sâu**, thay vì chỉ là thất bại.

---

## 📌 Slide 7 — Kiến trúc kỹ thuật

### Moodle 4.5 AI Subsystem Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        CARD BOX PLUGIN                          │
│                                                                 │
│  ┌─────────┐  ┌──────────────┐  ┌────────────────────────────┐ │
│  │ Template │  │  practice.js │  │        action.php          │ │
│  │(Mustache)│──│  (Frontend)  │──│  aihint / aiexplain /      │ │
│  │          │  │  AJAX calls  │  │  aihintimage               │ │
│  └─────────┘  └──────────────┘  └───────────┬────────────────┘ │
│                                              │                  │
└──────────────────────────────────────────────┼──────────────────┘
                                               │
                                               ▼
┌──────────────────────────────────────────────────────────────────┐
│                    MOODLE AI SUBSYSTEM (core_ai)                 │
│                                                                  │
│  ┌──────────────┐    ┌──────────────┐    ┌────────────────────┐ │
│  │   Placement   │───▶│   Manager    │───▶│     Provider       │ │
│  │  (Card Box)   │    │ process_     │    │                    │ │
│  │               │◀───│ action()     │◀───│  ┌──────────────┐ │ │
│  └──────────────┘    └──────────────┘    │  │   OpenAI      │ │ │
│                                          │  │   Azure AI    │ │ │
│                                          │  │   Ollama      │ │ │
│                                          │  │   (bất kỳ)    │ │ │
│                                          │  └──────────────┘ │ │
│                                          └────────────────────┘ │
└──────────────────────────────────────────────────────────────────┘
```

### Quy tắc vàng (Golden Rule)

> **Plugin không biết** AI Provider nào đang xử lý.  
> **Provider không biết** Plugin nào đang gọi.  
> **Mọi thứ đều đi qua Manager.**

→ Cho phép quản trị viên **thay đổi AI Provider** mà không ảnh hưởng đến Plugin.

---

## 📌 Slide 8 — Luồng hoạt động (User Flow)

### Flow AI Gợi ý

```
Sinh viên              Card Box Plugin           Moodle AI Manager          AI Provider
    │                       │                          │                       │
    │  Bấm "AI gợi ý"      │                          │                       │
    │──────────────────────▶│                          │                       │
    │                       │  generate_text(prompt)   │                       │
    │                       │─────────────────────────▶│                       │
    │                       │                          │  API call (prompt)    │
    │                       │                          │──────────────────────▶│
    │                       │                          │                       │
    │                       │                          │  Response (text)      │
    │                       │                          │◀──────────────────────│
    │                       │  Formatted response      │                       │
    │                       │◀─────────────────────────│                       │
    │  Hiển thị gợi ý      │                          │                       │
    │◀──────────────────────│                          │                       │
    │                       │                          │                       │
    │  Chọn đáp án          │                          │                       │
    │──────────────────────▶│                          │                       │
```

### Flow AI Gợi ý bằng hình

```
Sinh viên              Card Box Plugin           Moodle AI Manager          AI Provider
    │                       │                          │                       │
    │  Bấm "AI gợi ý       │                          │                       │
    │   bằng hình"          │                          │                       │
    │──────────────────────▶│                          │                       │
    │                       │  generate_image(prompt)  │                       │
    │                       │─────────────────────────▶│                       │
    │                       │                          │  API call (DALL-E/    │
    │                       │                          │  Stable Diffusion)    │
    │                       │                          │──────────────────────▶│
    │                       │                          │                       │
    │                       │                          │  Response (image URL) │
    │                       │                          │◀──────────────────────│
    │                       │  Image URL               │                       │
    │                       │◀─────────────────────────│                       │
    │  Hiển thị hình ảnh   │                          │                       │
    │◀──────────────────────│                          │                       │
```

---

## 📌 Slide 9 — Bảo mật & Quyền truy cập

### Capability-based Access Control

```
┌───────────────┬──────────────────┬──────────┐
│     Role      │  mod/cardbox:    │  Mặc định │
│               │  useai           │          │
├───────────────┼──────────────────┼──────────┤
│ Student       │  ✅ Cho phép      │  ALLOW   │
│ Teacher       │  ✅ Cho phép      │  ALLOW   │
│ EditTeacher   │  ✅ Cho phép      │  ALLOW   │
│ Manager       │  ✅ Cho phép      │  ALLOW   │
│ Guest         │  ❌ Không         │  -       │
└───────────────┴──────────────────┴──────────┘
```

### Các biện pháp bảo mật

| Biện pháp | Cách thực hiện |
|-----------|----------------|
| **Xác thực** | `require_login()` + `require_sesskey()` trên mọi AJAX call |
| **Phân quyền** | `require_capability('mod/cardbox:useai')` trước khi gọi AI |
| **Input validation** | `required_param($name, PARAM_TEXT)` — lọc input |
| **XSS Prevention** | DOM API (`createElement`) thay vì `innerHTML` cho hình ảnh |
| **Rate limiting** | Nút AI bị disable sau khi bấm → chặn spam request |
| **AI User Policy** | Moodle 4.5 yêu cầu SV chấp nhận AI Policy trước khi dùng |

---

## 📌 Slide 10 — So sánh Trước và Sau khi tích hợp AI

```
           TRƯỚC (không AI)                    SAU (có AI)
     ┌─────────────────────────┐        ┌─────────────────────────┐
     │                         │        │                         │
     │  Câu hỏi: Thủ đô       │        │  Câu hỏi: Thủ đô       │
     │  của Nhật Bản?          │        │  của Nhật Bản?          │
     │                         │        │                         │
     │  😰 Không biết...       │        │  🪄 AI gợi ý:           │
     │     Đoán bừa?           │        │  "Thành phố bắt đầu    │
     │                         │        │   bằng chữ T..."        │
     │  ✗ Chọn: Osaka          │        │                         │
     │                         │        │  💡 À! Chắc là Tokyo!   │
     │  Đáp án: Tokyo          │        │  ✓ Chọn: Tokyo          │
     │  (Chỉ thấy đáp án,     │        │                         │
     │   không hiểu tại sao)   │        │  ✅ ĐÚNG!               │
     │                         │        │                         │
     │  😕 Ghi nhớ máy móc    │        │  🧠 Hiểu sâu, nhớ lâu  │
     │                         │        │                         │
     └─────────────────────────┘        └─────────────────────────┘
```

### Khi trả lời sai

```
           TRƯỚC                                SAU
     ┌─────────────────────┐             ┌─────────────────────────┐
     │                     │             │                         │
     │  ✗ Sai: Osaka       │             │  ✗ Sai: Osaka           │
     │  ✓ Đúng: Tokyo      │             │  ✓ Đúng: Tokyo          │
     │                     │             │                         │
     │  (Hết. Không có     │             │  💡 AI Explain:          │
     │   giải thích gì.)   │             │  "Tokyo là thủ đô từ   │
     │                     │             │   năm 1868 khi Thiên    │
     │  → Tiếp tục         │             │   hoàng Minh Trị..."    │
     │                     │             │                         │
     │                     │             │  → Hiểu bản chất        │
     │                     │             │  → Không sai lần nữa    │
     └─────────────────────┘             └─────────────────────────┘
```

---

## 📌 Slide 11 — Lợi ích cho các bên

### 🎓 Đối với Sinh viên

- **Học chủ động hơn** — gợi ý giúp tự suy luận thay vì nhìn đáp án
- **Hiểu sâu hơn** — AI giải thích khi sai, không chỉ cho đáp án
- **Đa giác quan** — gợi ý bằng text + hình ảnh phù hợp nhiều phong cách học
- **Giảm frustration** — có "phao cứu sinh" khi gặp câu khó

### 👩‍🏫 Đối với Giáo viên

- **Tiết kiệm thời gian** — không cần soạn gợi ý cho từng thẻ
- **Nâng cao chất lượng** — AI tạo giải thích chi tiết cho mọi câu sai
- **Insights tốt hơn** — hiểu sinh viên đang vướng ở đâu

### 🏫 Đối với Tổ chức giáo dục

- **Tăng engagement** — sinh viên tương tác nhiều hơn với hệ thống
- **ROI cao** — tận dụng AI provider đã có sẵn (OpenAI/Azure)
- **Cạnh tranh** — bắt kịp xu hướng AI trong giáo dục
- **Tuân thủ** — AI Policy tích hợp sẵn trong Moodle 4.5

---

## 📌 Slide 12 — Yêu cầu triển khai

### Yêu cầu hệ thống

| Thành phần | Yêu cầu |
|------------|---------|
| **Moodle** | Phiên bản 4.5 trở lên (có AI Subsystem) |
| **AI Provider** | Cấu hình ít nhất 1 provider: OpenAI hoặc Azure AI |
| **Quyền** | Admin bật AI provider + cấp capability `useai` |

### Chi phí ước tính

```
┌────────────────────────────┬──────────────────────────────────┐
│  Hạng mục                  │  Chi phí                         │
├────────────────────────────┼──────────────────────────────────┤
│  Phát triển plugin         │  Đã hoàn thành ✅                │
│  OpenAI API (text)         │  ~$0.002 / lần gợi ý            │
│  OpenAI API (image)        │  ~$0.04 / lần tạo hình          │
│  100 SV × 10 lần/ngày     │  ~$0.60/ngày (chỉ text)         │
│  Triển khai & cấu hình    │  < 30 phút                       │
└────────────────────────────┴──────────────────────────────────┘
```

---

## 📌 Slide 13 — Hướng phát triển tiếp theo

### Giai đoạn tiếp theo (có thể mở rộng)

```
    ✅ Đã làm                    🔜 Tiếp theo                   🔮 Tương lai
┌──────────────────┐    ┌────────────────────────┐    ┌─────────────────────────┐
│                  │    │                        │    │                         │
│ • AI Gợi ý text │    │ • AI tạo flashcard     │    │ • AI Tutor chatbot      │
│ • AI Gợi ý hình │    │   từ tài liệu          │    │   tích hợp trong        │
│ • AI Giải thích  │    │ • AI kiểm tra đáp án   │    │   practice session      │
│   khi sai        │    │   thông minh (semantic) │    │ • Adaptive learning     │
│                  │    │ • AI tóm tắt tiến độ   │    │   path dựa trên AI      │
│                  │    │   học tập               │    │ • Voice interaction     │
│                  │    │                        │    │                         │
└──────────────────┘    └────────────────────────┘    └─────────────────────────┘
```

---

## 📌 Slide 14 — Tổng kết

### Card Box + AI = Học tập thông minh hơn

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│   🃏  Flashcard truyền thống    +    🤖  Trí tuệ nhân tạo       │
│                                                                 │
│   ═══════════════════════════════════════════════════════        │
│                                                                 │
│   📈  Spaced Repetition         +    💡  Giải thích thông minh  │
│   🔄  Luyện tập lặp lại         +    🪄  Gợi ý khi bí           │
│   📊  Thống kê tiến độ          +    🖼️  Gợi ý bằng hình ảnh   │
│                                                                 │
│   ═══════════════════════════════════════════════════════        │
│                                                                 │
│   🎯  Kết quả: HỌC SÂU HƠN — NHỚ LÂU HƠN — HIỂU RÕ HƠN     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### Một câu tóm tắt:

> *"Card Box biến mỗi lần luyện tập thành cơ hội học hỏi có ý nghĩa,*  
> *với AI đồng hành như một gia sư riêng cho từng sinh viên."*

---

## 📎 Phụ lục — Danh sách files đã thay đổi

| File | Vai trò |
|------|---------|
| `action.php` | 3 AJAX endpoints: `aihint`, `aihintimage`, `aiexplain` |
| `templates/practice_question_flashcard.mustache` | UI cho 3 nút AI |
| `js/practice.js` | Event handlers & AJAX calls cho AI buttons |
| `styles.css` | CSS styling cho AI components |
| `lang/en/cardbox.php` | 12 language strings cho AI features |
| `db/access.php` | Capability `mod/cardbox:useai` |
| `version.php` | Version 2026031000, release 1.1.0 |

---

*Tài liệu này được tạo cho mục đích thuyết trình về tính năng AI của plugin Card Box cho Moodle 4.5.*
