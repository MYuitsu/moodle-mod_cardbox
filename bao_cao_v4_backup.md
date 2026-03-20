<style>
@page { size: A4; margin: 20mm 18mm; }
body { font-family: "Times New Roman", serif; font-size: 12pt; }
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
    <div style="font-size:14pt;font-weight:700;margin-bottom:28px;">HỌC PHẦN: KHO DỮ LIỆU VÀ KHAI PHÁ DỮ LIỆU</div>
    <div style="font-size:16pt;font-weight:700;margin:8px 0;">ĐỀ TÀI:</div>
    <div style="font-size:18pt;font-weight:700;margin:4px 0;">DỰ ĐOÁN ĐIỂM NÓNG SỐT XUẤT HUYẾT</div>
    <div style="font-size:18pt;font-weight:700;margin:4px 0;">THEO XÃ/ PHƯỜNG</div>
    <div style="font-size:14pt;font-weight:700;margin-top:6px;">Dựa trên thời tiết (không gian - thời gian)</div>
    <div style="font-size:13pt;font-weight:700;margin-top:20px;">Mã học phần: CTH603</div>
  </div>
  <div style="margin-top:40mm;display:flex;justify-content:space-between;gap:20mm;">
    <div style="width:50%;font-size:12.5pt;">
      <div style="font-weight:700;margin-bottom:6px;">Giảng viên:</div>
      <div>PGS.TS. Nguyễn Thái Nghe</div>
    </div>
    <div style="width:50%;font-size:12.5pt;">
      <div style="font-weight:700;margin-bottom:6px;">Sinh viên:</div>
      <div>1. M2525017 - Nguyễn Thái Duy</div>
      <div>2. M2525021 - Đỗ Thị Cẩm Hằng</div>
    </div>
  </div>
</div>

<div style="page-break-after:always;"></div>

# MỤC LỤC

1. Tóm tắt  
2. Chương 1. Giới thiệu  
3. Chương 2. Tổng quan liên quan  
4. Chương 3. Dữ liệu và tiền xử lý  
5. Chương 4. Phương pháp  
6. Chương 5. Thiết kế hệ thống ứng dụng  
7. Chương 6. Thực nghiệm và kết quả  
8. Chương 7. Kết luận, thảo luận và phụ lục kỹ thuật  
9. Phụ lục A: Danh sách hình

<div style="page-break-after:always;"></div>

# DANH MỤC HÌNH

Hình 1. Tổng số ca SXH theo tháng  
Hình 2. Xu hướng ca SXH và mưa trung bình theo tháng  
Hình 3. Tương quan độ trễ: $y(t)$ và $rain(t-k)$  
Hình 4. Phân phối nhãn $y$ (log-count)  
Hình 5. Tỷ lệ $y=0$ theo tháng  
Hình 6. Var(y) vs Mean(y) theo xã  
Hình 7. So sánh baseline vs updated theo metrics  
Hình 8. Scatter $y$ thật vs $\hat{y}$ (HistGB Poisson)  
Hình 9. Feature importance (Permutation, -MAE)  
Hình 10. Toàn tỉnh (test): $y$ thật vs $y$ dự đoán theo ngày  
Hình 11. Calibration: mean(y) vs mean(ŷ)  
Hình 12. GLM residuals vs fitted  
Hình 13. Hotspot Hit@10 theo ngày  
Hình 14. Backtest 2025: MAE theo tháng  
Hình 15. Backtest 2025: Hit@10 theo tháng

<div style="page-break-after:always;"></div>

# BÁO CÁO MÔN HỌC (Thạc sĩ) — Khai phá dữ liệu

**Mã nguồn:** dự án trong thư mục hiện tại.

---

## Tóm tắt
Sốt xuất huyết (SXH) có mối liên hệ mạnh với các yếu tố khí tượng như lượng mưa, nhiệt độ và gió. Báo cáo này xây dựng pipeline dữ liệu ghép nối ca bệnh theo ngày–xã/phường với thời tiết theo ngày–xã/phường, thiết kế bộ đặc trưng có độ trễ (lag) và trung bình trượt (rolling), sau đó huấn luyện và so sánh các mô hình dự báo số ca: **Poisson Regression (GLM)**, **Gradient Boosting với Poisson loss** và **Linear Regression** (baseline). Cuối cùng, hệ thống được triển khai thành ứng dụng web (FastAPI + giao diện bản đồ) để nhập ngày, xuất **Top-K điểm nóng** và trực quan hóa trên bản đồ.

---

## CHƯƠNG 1. GIỚI THIỆU

### 1.1. Bối cảnh
SXH chịu ảnh hưởng bởi yếu tố khí tượng: mưa tạo điều kiện sinh sản của muỗi, nhiệt độ ảnh hưởng vòng đời muỗi và tốc độ phát triển của virus, gió ảnh hưởng khuếch tán và hoạt động bay [1], [3], [4]. Vì vậy, kết hợp dữ liệu ca bệnh theo địa bàn với dữ liệu thời tiết theo ngày có thể giúp xây dựng hệ thống **cảnh báo sớm** theo không gian–thời gian [2].

### 1.2. Vấn đề nghiên cứu
Bài toán đặt ra: với một ngày $t$, dự đoán xã/phường nào có **nguy cơ SXH cao** (hoặc số ca kỳ vọng cao), dựa trên:
- Xu hướng ca bệnh trước đó.
- Các biến thời tiết hiện tại và các biến thời tiết có độ trễ nhiều ngày trước.

### 1.3. Mục tiêu
- Xây dựng pipeline ghép nối dữ liệu ca bệnh – thời tiết theo xã/phường và theo ngày.
- Train và so sánh các mô hình dự báo số ca (Poisson Regression, Gradient Boosting với Poisson loss, Linear Regression).
- Thiết kế ứng dụng (backend + giao diện) nhận ngày đầu vào và xuất top điểm nóng SXH + hiển thị trên bản đồ.
- Mô phỏng cập nhật mô hình theo dữ liệu mới (train tiếp đến 6/2025).

### 1.4. Phạm vi
- **Địa bàn:** các xã/phường trong tập ca bệnh (thời tiết được lọc theo danh sách này để giảm tải).
- **Thời gian dữ liệu:** 01/2023 – 12/2025; tập test minh họa: 01/2025.
- **Nhãn dự đoán:** số ca theo ngày tại mỗi xã/phường (hoặc điểm nguy cơ từ số ca kỳ vọng).

---

## CHƯƠNG 2. TỔNG QUAN LIÊN QUAN

### 2.1. SXH và yếu tố thời tiết (tóm tắt)
- **Mưa (rain_mm):** tăng nơi sinh sản; ảnh hưởng thường có độ trễ 1–3 tuần tùy bối cảnh [3], [4], [5].
- **Nhiệt độ (temp_c):** tăng tốc vòng đời muỗi/virus đến ngưỡng tối ưu [3], [5].
- **Gió (wind_dir_deg, wind_speed_ms):** ảnh hưởng phân tán muỗi và hoạt động bay [3].

### 2.2. Các hướng mô hình hóa phổ biến
- Mô hình thống kê cho dữ liệu đếm: Poisson/Negative Binomial (GLM) [6]–[9].
- ML: Gradient Boosting, Random Forest, XGBoost; các biến thể dự báo chuỗi thời gian [10]–[14].
- Không gian–thời gian: mô hình phân cấp, Gaussian Process, spatio-temporal deep learning.

### 2.3. Tài liệu tham khảo chính
- Poisson GLM và dữ liệu đếm: [6]–[9], [15].
- Gradient Boosting với Poisson loss: [10]–[16].
- Dịch tễ và cảnh báo sớm SXH: [1]–[5].
- Nguồn dữ liệu thời tiết: [17].

---

## CHƯƠNG 3. DỮ LIỆU VÀ TIỀN XỬ LÝ

### 3.1. Mô tả dữ liệu

**Dữ liệu ca bệnh `sxh_sap_nhap_mapped.csv`**
Trường chính sử dụng:
- `XA_PHUONG_MOI`: xã/phường chuẩn hóa.
- `NGAY_KHAM`: ngày khám (ngày phát hiện ca).

**Dữ liệu thời tiết `weather_daily_2023_2025.csv`**
- `TenXa`: xã/phường.
- `date`: ngày.
- `rain_mm`, `temp_c`, `wind_dir_deg`, `wind_speed_ms`.
Nguồn thời tiết lấy từ Open‑Meteo [17].

**Dữ liệu ranh giới `tien_giang_xa.geojson`**
Đa giác ranh xã/phường dùng để vẽ bản đồ nguy cơ (fallback). Trong app, nếu có `centroid_tenxa_wgs84.csv` thì ưu tiên bản đồ điểm theo centroid để hiển thị nhanh.

**Thống kê mô tả (để hiểu độ khó của bài toán)**

Vì bài toán dự báo SXH theo **đơn vị ngày × xã/phường** (panel data), cần nhìn rõ: (i) mức độ thưa của nhãn (nhiều ngày không có ca), (ii) quy mô theo không gian–thời gian, (iii) phân phối lệch phải và đuôi dài.

Thống kê dưới đây được tính trực tiếp từ 2 file dữ liệu đầu vào chính của pipeline (sau khi loại bản ghi lỗi ngày/tên xã):

- Số dòng ca bệnh gốc: **12,406**
- Số xã/phường xuất hiện trong ca bệnh: **57**
- Khoảng thời gian ca bệnh: **2023-01-01 → 2025-12-18**
- Số dòng thời tiết theo xã-phường-ngày: **59,565**
- Khoảng thời gian thời tiết: **2023-01-01 → 2025-12-18**
- Panel sau khi ghép thời tiết + ca bệnh (mỗi dòng là 1 xã-phường trong 1 ngày): **59,565**
- Số xã/phường có đủ panel sau khi ghép: **55**
- Số ngày: **1,083**
- Tỷ lệ ngày có 0 ca: **92.32%**
- Trung bình số ca/ngày/xã: **0.206**; trung vị: **0**
- Bách phân vị 95%: **1**; giá trị lớn nhất: **38**

Hệ quả phương pháp luận:
- Dữ liệu có dấu hiệu **zero-inflated** (rất nhiều số 0) và “đỉnh” hiếm nhưng cao; do đó RMSE/MAE có thể bị chi phối bởi một số ngày bất thường.
- Metric phục vụ vận hành (Top-K điểm nóng) là cần thiết, vì mục tiêu thực tế thường là “ưu tiên đúng địa bàn” hơn là “khớp tuyệt đối từng con số”.

### 3.2. Chuẩn hóa, ghép nối và tạo nhãn
- Chuẩn hóa encoding UTF-8-SIG, loại bỏ khoảng trắng thừa trong tên xã.
- Ghép theo khóa `(xa, date)`.

Làm rõ hơn để tránh sai lệch do join và sai lệch do nhập liệu:

- **Chuẩn hoá khoá ghép:** tên xã/phường được `strip()`; ngày được ép kiểu `datetime` (bản ghi nào không parse được sẽ bị loại).
- **Tạo label theo ngày:** dữ liệu ca bệnh gốc có thể có nhiều dòng trong cùng (xã, ngày). Pipeline gom nhóm để tạo nhãn đếm $y_{x,t}$.
- **Ghép panel:** sử dụng bảng thời tiết như “khung panel” theo (xã, ngày), sau đó ghép trái nhãn vào. Nếu một (xã, ngày) không có ca bệnh thì gán $y=0$.

Gán $y=0$ là hợp lý cho bài toán đếm ca, nhưng đồng thời làm tăng tỷ lệ số 0; vì vậy phần mô hình hoá (Poisson) và đánh giá (Hit@K/Outbreak) được thiết kế để phù hợp đặc tính này.

**Lọc dữ liệu thời tiết để giảm tải**
Chỉ giữ thời tiết cho các xã xuất hiện trong ca bệnh:

$$X = \{x\mid x\in \texttt{XA\_PHUONG\_MOI}\}$$

Giữ lại mọi bản ghi thỏa `TenXa ∈ X`.

**Tạo biến mục tiêu (label)**
Gom nhóm số ca theo ngày và xã/phường:

$$y_{x,t} = \sum \mathbf{1}(\text{case ở }x\ \text{và}\ \texttt{NGAY\_KHAM}=t)$$

Ghi chú về ý nghĩa nhãn:

- `NGAY_KHAM` là ngày khám/phát hiện ca, không nhất thiết là ngày khởi phát. Điều này tạo ra **độ trễ quan sát** so với các yếu tố thời tiết, khiến độ trễ tối ưu (lag) trong thực tế có thể bị “dịch”.
- Trong phạm vi bài toán môn học, ta chấp nhận `NGAY_KHAM` như một proxy thực dụng; phần thảo luận sẽ nêu rõ đây là một nguồn sai lệch/không chắc chắn.

### 3.3. Hình và EDA
Các hình được sinh bởi `make_figures_and_metrics_v2.py` và lưu trong `report_figures/`.

| Hình | Tên file | Nội dung |
|---:|---|---|
| 1 | `fig_cases_monthly.png` | Tổng số ca SXH theo tháng |
| 2 | `fig_rain_vs_cases_monthly.png` | Xu hướng ca SXH và mưa trung bình theo tháng |
| 3 | `fig_lag_corr_rain.png` | Tương quan độ trễ: $y(t)$ và $rain(t-k)$ |
| 4 | `fig_y_distribution.png` | Phân phối nhãn $y$ (log-count) |
| 5 | `fig_zero_rate_monthly.png` | Tỷ lệ $y=0$ theo tháng |
| 6 | `fig_mean_variance_by_xa.png` | Var(y) vs Mean(y) theo xã |
| 7 | `fig_compare_baseline_vs_updated.png` | So sánh baseline vs updated theo metrics |
| 8 | `fig_pred_vs_true.png` | Scatter $y$ thật vs $\hat{y}$ (HistGB Poisson) |
| 9 | `fig_feature_importance.png` | Feature importance (Permutation, -MAE) |
| 10 | `fig_province_daily_true_vs_pred.png` | Toàn tỉnh (test): $y$ thật vs $y$ dự đoán theo ngày |
| 11 | `fig_calibration_mean_y_vs_pred.png` | Calibration: mean(y) vs mean(ŷ) |
| 12 | `fig_glm_residuals_vs_fitted.png` | GLM residuals vs fitted |
| 13 | `fig_hit10_over_time.png` | Hotspot Hit@10 theo ngày |
| 14 | `fig_backtest_mae_by_month.png` | Backtest 2025: MAE theo tháng |
| 15 | `fig_backtest_hit10_by_month.png` | Backtest 2025: Hit@10 theo tháng |

---

**EDA bổ sung phục vụ *chọn mô hình* (Poisson GLM vs HistGB Poisson)**

Ba biểu đồ dưới đây cho thấy dữ liệu nhãn là **count**, nhiều số 0 và có dấu hiệu **over‑dispersion**, là cơ sở để chọn Poisson GLM (tuyến tính, dễ diễn giải) và HistGB với Poisson loss (phi tuyến).

**Hình 4 (fig_y_distribution.png). Phân phối nhãn $y$ (log-count)**

![](report_figures/fig_y_distribution.png)

- Nhãn lệch phải mạnh, đỉnh ở 0 → ưu tiên mô hình count và metric xếp hạng điểm nóng.

**Hình 5 (fig_zero_rate_monthly.png). Tỷ lệ $y=0$ theo tháng**

![](report_figures/fig_zero_rate_monthly.png)

- Zero‑rate thay đổi theo tháng → cần đặc trưng lịch và mô hình học được mùa vụ.

**Hình 6 (fig_mean_variance_by_xa.png). Var(y) vs Mean(y) theo xã**

![](report_figures/fig_mean_variance_by_xa.png)

- Var > Mean ở nhiều xã → có over‑dispersion, cần mô hình linh hoạt hơn GLM thuần.

### 3.4. Bảng số liệu bổ sung

**Bảng 1. Tổng số ca theo năm (toàn tỉnh)**

| year | total_cases | days | avg_per_day | max_day |
| --- | --- | --- | --- | --- |
| 2023 | 5240 | 361 | 14.515 | 45 |
| 2024 | 3723 | 356 | 10.458 | 44 |
| 2025 | 3443 | 342 | 10.067 | 51 |

**Bảng 2. Top 12 tháng có tổng ca cao nhất**

| month | total_cases | avg_per_day | max_day |
| --- | --- | --- | --- |
| 2023-10-01 00:00:00 | 769 | 24.806 | 45 |
| 2025-10-01 00:00:00 | 753 | 24.290 | 51 |
| 2023-11-01 00:00:00 | 648 | 21.600 | 42 |
| 2023-09-01 00:00:00 | 568 | 19.586 | 36 |
| 2024-10-01 00:00:00 | 543 | 17.516 | 44 |
| 2023-03-01 00:00:00 | 480 | 15.484 | 29 |
| 2023-02-01 00:00:00 | 475 | 16.964 | 33 |
| 2024-11-01 00:00:00 | 469 | 15.633 | 29 |
| 2024-12-01 00:00:00 | 450 | 14.516 | 26 |
| 2023-04-01 00:00:00 | 416 | 13.867 | 25 |
| 2025-09-01 00:00:00 | 413 | 14.241 | 29 |
| 2023-08-01 00:00:00 | 397 | 12.806 | 22 |

**Bảng 3. Top 15 xã/phường có tổng ca cao nhất (2023–2025)**

| xa | total_cases | days_with_cases |
| --- | --- | --- |
| Xã Cái Bè | 7814 | 1005 |
| Xã Hội Cư | 797 | 480 |
| Xã Thanh Hưng | 502 | 309 |
| Xã Tân Đông | 260 | 209 |
| Xã An Hữu | 239 | 198 |
| Xã Gia Thuận | 201 | 149 |
| Xã Mỹ Lợi | 191 | 153 |
| Xã Chợ Gạo | 168 | 147 |
| Xã Mỹ Đức Tây | 150 | 130 |
| Xã Bình Phú | 125 | 118 |
| Xã Vĩnh Bình | 117 | 95 |
| Phường Mỹ Phước | 113 | 99 |
| Xã Mỹ Thiện | 100 | 90 |
| Phường Cai Lậy | 93 | 84 |
| Xã Châu Thành | 90 | 81 |

**Bảng 4. Tỷ lệ ngày y=0 theo xã (cao nhất / thấp nhất)**

_Nhóm tỷ lệ y=0 cao nhất:_

| xa | is_zero |
| --- | --- |
| Xã Bình Ninh | 0.999 |
| Xã Tân Thuận Bình | 0.996 |
| Xã Tân Phước 3 | 0.996 |
| Xã Tân Phước 2 | 0.995 |
| Xã Kim Sơn | 0.994 |
| Xã An Thạnh Thủy | 0.993 |
| Xã Vĩnh Kim | 0.991 |
| Phường Đạo Thạnh | 0.986 |
| Phường Long Thuận | 0.984 |
| Xã Phú Thành | 0.984 |

_Nhóm tỷ lệ y=0 thấp nhất:_

| xa | is_zero |
| --- | --- |
| Xã Cái Bè | 0.072 |
| Xã Hội Cư | 0.557 |
| Xã Thanh Hưng | 0.715 |
| Xã Tân Đông | 0.807 |
| Xã An Hữu | 0.817 |
| Xã Mỹ Lợi | 0.859 |
| Xã Gia Thuận | 0.862 |
| Xã Chợ Gạo | 0.864 |
| Xã Mỹ Đức Tây | 0.880 |
| Xã Bình Phú | 0.891 |

**Bảng 5. Thống kê mô tả thời tiết (toàn bộ xã/ngày)**

| var | count | mean | std | min | 10% | 50% | 90% | max |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| rain_mm | 59565.000 | 6.349 | 8.085 | 0.000 | 0.000 | 3.200 | 17.400 | 81.700 |
| temp_c | 59565.000 | 27.324 | 1.375 | 22.300 | 25.800 | 27.200 | 29.000 | 33.200 |
| wind_speed_ms | 59565.000 | 2.536 | 0.915 | 0.620 | 1.470 | 2.390 | 3.860 | 6.280 |
| wind_dir_deg | 59565.000 | 160.619 | 84.403 | 0.000 | 40.000 | 159.000 | 249.000 | 360.000 |

**Bảng 6. Top 10 độ trễ có tương quan cao nhất giữa mưa và ca (Pearson)**

| lag | pearson_corr |
| --- | --- |
| 29 | 0.198 |
| 30 | 0.188 |
| 23 | 0.179 |
| 27 | 0.176 |
| 25 | 0.176 |
| 24 | 0.162 |
| 10 | 0.161 |
| 22 | 0.161 |
| 28 | 0.160 |
| 26 | 0.159 |

---

### 3.5. Tóm tắt chương
Tóm lại, dữ liệu mang tính panel theo ngày–xã, nhiều số 0 và có mùa vụ rõ. Các bước chuẩn hóa, ghép nối và tạo nhãn đảm bảo dữ liệu nhất quán cho huấn luyện và đánh giá.

## CHƯƠNG 4. PHƯƠNG PHÁP

### 4.1. Đặt bài toán
Với mỗi xã/phường $x$ và ngày $t$, dự đoán số ca kỳ vọng $\hat{y}_{x,t}$ từ vector đặc trưng $f_{x,t}$ (tạo ra từ thời tiết và lịch sử ca bệnh).


### 4.2. Lý do chọn **Poisson GLM**, **HistGB (Poisson loss)** và **Linear Regression**

Quan sát dữ liệu (Chương 3) cho thấy nhãn là **count theo ngày**, nhiều số 0, phân phối lệch phải và có **over‑dispersion**. Mô hình cần đảm bảo **dự đoán không âm**, tối ưu theo phân phối count, và phục vụ mục tiêu **xếp hạng điểm nóng**.

**Poisson GLM (PoissonRegressor) — baseline có thể giải thích** [6]–[9], [15]
- Log-link đảm bảo $\hat{y}\ge 0$ và cho phép diễn giải tác động (hướng, cường độ) của nhóm biến mưa/nhiệt/gió/lịch.
- Ổn định, ít tham số, dễ tái tạo, làm mốc so sánh rõ ràng cho mô hình phi tuyến.
- Phù hợp để kiểm tra nhanh giả thuyết “tuyến tính trên log‑rate” có đủ giải thích dữ liệu hay không.

**HistGradientBoosting (loss="poisson") — bắt phi tuyến nhưng vẫn đúng bản chất count** [10]–[16]
- Tối ưu Poisson loss nên phù hợp dữ liệu đếm và nhiều số 0 hơn so với MSE.
- Bắt được **ngưỡng** (mưa thấp/đủ/cao), **tương tác** (mưa × nhiệt × mùa) và các cấu trúc mà GLM bỏ sót.
- Thực tế thường cải thiện ở các đỉnh/đợt bùng phát, là phần quan trọng trong cảnh báo.

**Linear Regression — baseline tuyến tính**
- Hồi quy tuyến tính chuẩn, dễ huấn luyện và diễn giải, làm mốc so sánh với mô hình Poisson.
- Cho thấy ảnh hưởng của “chỉ tuyến tính” khi không ràng buộc phân phối đếm.
- Hạn chế: có thể dự đoán âm, cần clip về 0 khi diễn giải số ca.

**Vì sao không chọn mô hình khác** (sau khi xem xét dữ liệu):
- **Gaussian regression (Linear/Ridge/Lasso):** có thể dự đoán âm, giả định phương sai không phù hợp dữ liệu đếm.
- **RF/GBM với MSE:** không tối ưu theo phân phối count, calibration kém ở vùng $y$ nhỏ/0.
- **XGBoost/LightGBM:** mạnh nhưng yêu cầu tuning/phụ thuộc lớn, vượt phạm vi demo môn học.
- **Classification thuần túy:** mất thông tin mức độ, khó xếp hạng Top‑K nếu dùng một mình.
- **ARIMA/Prophet:** phù hợp chuỗi đơn, khó mở rộng cho panel theo xã/phường.

Các mô hình count nâng cao (Negative Binomial, Zero‑Inflated/Hurdle) được để ở phần hướng phát triển vì cần thiết kế và kiểm định thêm.



### 4.3. Feature Engineering

**Độ trễ (lag) thời tiết**
Ví dụ với mưa:

$$rain^{(k)}_{x,t} = rain_{x,t-k},\quad k\in\{1,3,7,14\}$$

Áp dụng tương tự cho `temp_c`, `wind_speed_ms`, và biểu diễn hướng gió bằng sin/cos.

Giải thích lựa chọn tập lag {1, 3, 7, 14}:

- Đây là tập “gọn” nhưng bao phủ được các mức trễ ngắn–trung hạn (1–2 tuần).
- Trực giác dịch tễ: mưa ảnh hưởng tới ổ bọ gậy → quần thể muỗi; nhiệt độ ảnh hưởng vòng đời muỗi/virus; các hiệu ứng này hiếm khi phản ánh tức thời ngay ngày hôm sau.
- Trên dữ liệu thực tế, biểu đồ tương quan lag (Hình 3) giúp kiểm tra xem có “cửa sổ trễ” nào nổi bật hay không.

**Trung bình trượt (rolling)**
Ví dụ:

$$\overline{rain}^{(w)}_{x,t} = \frac{1}{w}\sum_{i=1}^{w} rain_{x,t-i},\quad w\in\{7,14\}$$

Rolling giúp mô hình nắm “tích luỹ điều kiện” (ví dụ mưa kéo dài 1–2 tuần) thay vì chỉ nhìn một ngày đơn lẻ. Việc dùng $t-i$ (bắt đầu từ 1) đảm bảo chỉ dùng quá khứ, tránh rò rỉ thông tin từ chính ngày $t$.

**Lag ca bệnh**

$$cases^{(k)}_{x,t} = y_{x,t-k}$$

Giúp mô hình nắm động lực bùng phát.

Lưu ý quan trọng: lag/rolling của **cases** thường có sức dự báo mạnh nhất, nhưng cũng khiến mô hình có xu hướng “bám quán tính” (forecast theo đà) và có thể bỏ lỡ các thay đổi cấu trúc do can thiệp y tế hoặc yếu tố xã hội. Vì vậy báo cáo cần kèm metric Top-K/Outbreak để đánh giá theo mục tiêu cảnh báo.

### 4.4. Các mô hình

**Mô hình 1: Poisson Regression (GLM baseline)**
Giả định:

$$y_{x,t}\sim \text{Poisson}(\lambda_{x,t})$$

Hàm liên kết log:

$$\log(\lambda_{x,t}) = \beta_0 + \beta^T f_{x,t}$$

**Mô hình 2: Gradient Boosting với Poisson loss**
Dùng cây tăng cường (boosting) để học quan hệ phi tuyến, tối ưu mục tiêu Poisson, phù hợp dữ liệu đếm và nhiều số 0.

**Mô hình 3: Linear Regression (baseline)**
Linear Regression dự báo:

$$\hat{y}_{x,t} = \beta_0 + \beta^T f_{x,t}$$

Mô hình dùng làm baseline tuyến tính để đối chiếu với Poisson (count) và HistGB (phi tuyến). Khi diễn giải số ca, dự báo âm được clip về 0.

### 4.5. Train/test và đánh giá
- Train: 01/2023 – 12/2024
- Test: 01/2025 (một cửa sổ ngắn để kiểm thử)
- Update train: huấn luyện lại với dữ liệu đến 06/2025

**Giải thích “2 cách train” trong dự án:**
- **Baseline training (mô hình ban đầu):** huấn luyện với dữ liệu đến `train_end = 2024-12-31`. Đây là mô phỏng giai đoạn triển khai lần đầu.
- **Updated training (mô hình sau cập nhật):** huấn luyện lại với dữ liệu mở rộng đến `update_train_end = 2025-06-30` nhưng vẫn giữ **cùng cửa sổ test** (01/2025) để so sánh công bằng.

Trong vận hành thực tế, baseline tương ứng “mô hình đang chạy”, còn updated là “mô hình được cập nhật định kỳ khi có dữ liệu mới”. Việc giữ nguyên cửa sổ test giúp đánh giá liệu cập nhật dữ liệu có cải thiện dự đoán trên giai đoạn mục tiêu hay không.

**Chỉ số đánh giá**
- MAE:

$$\mathrm{MAE}=\frac{1}{N}\sum_{i=1}^N |y_i-\hat{y}_i|$$

- RMSE:

$$\mathrm{RMSE}=\sqrt{\frac{1}{N}\sum_{i=1}^N (y_i-\hat{y}_i)^2}$$

- Poisson deviance:

$$D = 2\sum_i \Big(y_i\log\frac{y_i}{\hat{y}_i} - (y_i-\hat{y}_i)\Big)$$

Ý nghĩa: Poisson deviance đánh giá “độ phù hợp theo phân phối đếm”, thường hợp lý hơn MSE khi nhãn là count và rất lệch phải. Trong triển khai thực nghiệm, báo cáo trình bày deviance ở hai dạng:

- Dạng tổng (phù hợp để so sánh trực tiếp khi N cố định).
- Dạng trung bình (Mean Poisson Deviance) trong bảng so sánh baseline vs updated.

Hai dạng này chỉ khác nhau bởi hệ số $1/N$; xu hướng so sánh (mô hình nào tốt hơn) không đổi nếu N giống nhau.

- Hotspot Hit@K:
Với mỗi ngày, lấy xã có số ca thực tế cao nhất (true top-1). Hit@K = 1 nếu xã này nằm trong top-K dự đoán; báo cáo là trung bình theo ngày.

---

## CHƯƠNG 5. THIẾT KẾ HỆ THỐNG ỨNG DỤNG

### 5.1. Kiến trúc tổng quan
- Pipeline train: đọc dữ liệu → tạo label → join weather → tạo feature → train 3 mô hình → lưu artifacts.
- Backend (FastAPI): load model + feature store; khi suy luận dựng lại cửa sổ 14 ngày cho từng xã bằng lịch sử + Open‑Meteo (archive cho quá khứ thiếu, forecast cho ngày mục tiêu) để tính lag/rolling.
- Frontend: nhập ngày + top‑K, hiển thị bảng kết quả và bản đồ điểm (centroid); click vào xã để xem lịch sử thời tiết 14 ngày.

### 5.2. Các chế độ dự đoán
- **Theo vị trí hiện tại**: dùng định vị trình duyệt → chọn xã gần nhất → dựng cửa sổ 14 ngày rồi dự đoán ngày mục tiêu.
- **Top‑K theo ngày**: dự đoán cho tất cả xã theo ngày mục tiêu (dựa trên centroid từng xã) và xếp hạng top‑K.

Để minh bạch trong vận hành, backend có 3 endpoint chính tương ứng:

- `/api/predict`: chỉ nhận ngày hôm nay/tương lai và trong phạm vi feature store (endpoint nội bộ, UI không gọi trực tiếp).
- `/api/predict_here`: dự đoán cho xã gần nhất theo vị trí người dùng, dựng cửa sổ 14 ngày bằng lịch sử + Open‑Meteo.
- `/api/predict_now_top10`: dự đoán cho tất cả xã theo ngày mục tiêu (forecast theo centroid) và trả top‑K kèm thời tiết.

Ngoài dự đoán, phản hồi API trả thêm:

- `weather_mode` ∈ {historical, forecast, forecast_per_commune}: nguồn thời tiết đang dùng.
- `weather_used`: snapshot thời tiết (mưa/nhiệt/gió/hướng) đã được đưa vào feature.
- `history_by_xa`: lịch sử thời tiết 14 ngày theo xã để hiển thị chi tiết trong UI.

Phần này giúp người dùng (và người đọc báo cáo) kiểm tra tính hợp lý và tránh hiểu nhầm “mô hình dựa trên dữ liệu nào” tại thời điểm suy luận.

---

## CHƯƠNG 6. THỰC NGHIỆM VÀ KẾT QUẢ

### 6.1. Thiết lập thí nghiệm
- So sánh mô hình đếm ca: Poisson Regression, HistGradientBoosting (Poisson loss) và Linear Regression.
- Thiết lập lag: {1,3,7,14}, rolling {7,14}.

### 6.2. Kết quả định lượng
Bảng dưới lấy từ `report_figures/metrics.csv` (tập test 01/2025):

| Model | MAE | RMSE | PoissonDeviance | Hit@5 | Hit@10 |
|---|---:|---:|---:|---:|---:|
| PoissonRegressor(GLM) | 0.2369 | 0.5969 | 434.88 | 0.9333 | 0.9333 |
| HistGB(poisson) | 0.1374 | 0.4866 | 237.59 | 0.9333 | 0.9333 |
| LinearRegression | 0.1494 | 0.4569 | 359.88 | 0.9333 | 0.9333 |

#### 6.2.1. So sánh mô hình trước và sau cập nhật (baseline vs updated)
Trong pipeline huấn luyện, dự án thực hiện 2 lần huấn luyện:
- **Baseline:** train đến 2024-12-31
- **Updated:** retrain đến 2025-06-30

Để tránh nhầm lẫn (và để phục vụ báo cáo), dự án lưu thêm file so sánh: `artifacts/compare_baseline_vs_update.csv`.

**Bảng 2. So sánh metrics baseline vs updated (cùng cửa sổ test 01/2025)**

| Stage | Train end | Model | MAE | RMSE | Mean Poisson Deviance | Outbreak Acc@0.5 |
|---|---|---|---:|---:|---:|---:|
| baseline | 2024-12-31 | poisson_regression | 0.1497 | 0.5345 | 0.2946 | 0.9564 |
| baseline | 2024-12-31 | hgb_poisson | 0.1285 | 0.3989 | 0.2755 | 0.9600 |
| baseline | 2024-12-31 | linear_regression | 0.1458 | 0.4771 | 0.3006 | 0.9491 |
| updated | 2025-06-30 | poisson_regression | 0.1311 | 0.4390 | 0.2774 | 0.9588 |
| updated | 2025-06-30 | hgb_poisson | 0.1124 | 0.3408 | 0.2021 | 0.9612 |
| updated | 2025-06-30 | linear_regression | 0.1371 | 0.4634 | 0.3248 | 0.9479 |

**Nhận xét:**
- Khi cập nhật tập train (thêm dữ liệu đến 06/2025), các mô hình đếm ca đều cải thiện trên cửa sổ test 01/2025 (MAE/RMSE giảm).
- Với mô hình tốt nhất (hgb_poisson), mức giảm MAE từ 0.1285 → 0.1124 cho thấy việc cập nhật dữ liệu giúp mô hình “bắt nhịp” tốt hơn với phân phối mới.
- Outbreak Acc@0.5 cải thiện nhẹ, phù hợp với nhận định rằng cập nhật dữ liệu giúp ổn định dự đoán vùng có nguy cơ.

**Hình 7 (fig_compare_baseline_vs_updated.png). So sánh baseline vs updated theo các metrics**

![](report_figures/fig_compare_baseline_vs_updated.png)

**Nhận xét:**
- Các metric thấp hơn (MAE/RMSE/Deviance) ở bản **updated** cho thấy dữ liệu train mới hơn giúp mô hình bám phân phối test tốt hơn.
- Acc@0.5 tăng nhẹ, phản ánh cải thiện ổn định ở quyết định nguy cơ.

### 6.3. Kết quả trực quan

**Hình 8 (fig_pred_vs_true.png). Scatter $y$ thật vs $\hat{y}$ dự đoán (HistGB Poisson) trên tập test**

![](report_figures/fig_pred_vs_true.png)

Hầu hết điểm tập trung ở $y=0$, một số đỉnh cao bị under‑predict do dữ liệu lệch phải.

**Hình 9 (fig_feature_importance.png). Top 20 Feature Importance (Permutation Importance, scoring = -MAE)**

![](report_figures/fig_feature_importance.png)

Các nhóm đặc trưng quan trọng thường là lag/rolling của ca bệnh, mưa/nhiệt, và đặc trưng lịch; cần đọc theo nhóm do tương quan mạnh giữa các lag.

---

#### 6.3.1. Dự đoán theo ngày ở mức *toàn tỉnh* (test window)

Biểu đồ tổng hợp theo ngày (cộng dự đoán theo tất cả xã) giúp trả lời câu hỏi: **mô hình nào bám được xu hướng toàn tỉnh** và bớt “phẳng” khi có đỉnh.

**Hình 10 (fig_province_daily_true_vs_pred.png). Toàn tỉnh (test): $y$ thật vs $y$ dự đoán theo ngày (GLM vs HistGB)**

![](report_figures/fig_province_daily_true_vs_pred.png)

GLM bám nền tốt hơn, HistGB linh hoạt hơn ở các đoạn biến thiên mạnh.

#### 6.3.2. Calibration: mean(y) vs mean(ŷ) theo bins dự đoán

Calibration cho biết dự đoán có “đúng thang” không (ví dụ: những điểm có ŷ≈0.5 thì trung bình y thực có gần 0.5 không).

**Hình 11 (fig_calibration_mean_y_vs_pred.png). Calibration curve (bin theo ŷ): mean(y) vs mean(ŷ)**

![](report_figures/fig_calibration_mean_y_vs_pred.png)

Đường gần đường chéo biểu thị dự đoán có thang tốt; lệch ở vùng ŷ lớn cho thấy giới hạn của GLM.

#### 6.3.3. Chẩn đoán GLM: Deviance residuals vs fitted

Residual plot giúp kiểm tra nhanh mô hình tuyến tính có “bỏ sót cấu trúc” không (pattern theo ŷ).

**Hình 12 (fig_glm_residuals_vs_fitted.png). GLM: Deviance residuals vs fitted (test)**

![](report_figures/fig_glm_residuals_vs_fitted.png)

Pattern theo ŷ là dấu hiệu GLM đang thiếu tương tác/phi tuyến.

#### 6.3.4. Metric vận hành theo thời gian: Hotspot Hit@10 theo ngày

Hit@K theo ngày cho thấy tính ổn định của quyết định “ưu tiên xã nào” trong vận hành.

**Hình 13 (fig_hit10_over_time.png). Hotspot Hit@10 theo ngày (test)**

![](report_figures/fig_hit10_over_time.png)

#### 6.3.5. Backtest theo tháng trong năm 2025 (train_end cố định)

Vì cửa sổ test 01/2025 khá ngắn, báo cáo bổ sung backtest theo **từng tháng** trong 2025 (mô hình vẫn train đến 2024-12-31) để quan sát độ ổn định theo mùa.

**Hình 14 (fig_backtest_mae_by_month.png). Backtest 2025: MAE theo tháng**

![](report_figures/fig_backtest_mae_by_month.png)

**Hình 15 (fig_backtest_hit10_by_month.png). Backtest 2025: Hit@10 theo tháng**

![](report_figures/fig_backtest_hit10_by_month.png)

File số liệu backtest được xuất ra: `report_figures/metrics_backtest_monthly.csv`.

### 6.4. Phân tích
Kết quả cho thấy:
- HistGB(poisson) thường vượt GLM ở các đoạn có biến thiên mạnh và ở metric Poisson deviance.
- Dữ liệu nhiều số 0 khiến mô hình dễ bị kéo về 0; vì vậy Hit@K phản ánh tốt mục tiêu “điểm nóng”.
- Sai số cao chủ yếu ở ngày đột biến; cần thêm biến xã hội/can thiệp để cải thiện.

---

## CHƯƠNG 7. KẾT LUẬN, THẢO LUẬN VÀ PHỤ LỤC KỸ THUẬT

### 7.1. Kết luận
- Đã xây dựng pipeline dữ liệu và ứng dụng dự đoán theo ngày.
- So sánh các mô hình đếm ca cho thấy mô hình phi tuyến (boosting) có xu hướng tốt hơn về MAE/RMSE/Deviance và Hotspot Hit@K.

### 7.2. Hạn chế
- Ca bệnh theo ngày khám (có độ trễ so với ngày khởi phát).
- Thiếu biến can thiệp và biến xã hội.
- Thời tiết theo điểm đại diện xã/phường (xấp xỉ).

### 7.3. Hướng phát triển
- Negative Binomial / Zero-Inflated Poisson để xử lý over-dispersion và nhiều số 0.
- Forecast horizon: dự đoán trước 7–14 ngày.
- Bổ sung dữ liệu dân số, vệ sinh môi trường, chiến dịch diệt lăng quăng.
- Giám sát drift và cập nhật mô hình định kỳ.

---


### 7.4. Phân tích bổ sung và thảo luận

**Mùa vụ, độ trễ và độ nhạy**
Phân tích mùa vụ cho thấy đỉnh ca bệnh tập trung vào mùa mưa. Cơ chế có thể giải thích bằng chuỗi tác động: mưa tạo điều kiện hình thành ổ bọ gậy, quần thể muỗi tăng sau một khoảng trễ sinh học, sau đó ca bệnh tăng theo. Do đó, đặc trưng độ trễ (lag) và trung bình trượt (rolling) là cần thiết để mô hình nắm được quy luật này. Trong thực tế, khoảng trễ tối ưu có thể dao động theo điều kiện vi mô từng địa bàn, mật độ dân cư và cường độ can thiệp y tế.

Để kiểm tra độ nhạy, có thể tăng/giảm tập lag (ví dụ {1,3,7,14} ↔ {1,7,14,21}) và so sánh MAE/Hit@K. Mục tiêu không chỉ là tối ưu sai số trung bình mà còn giữ ổn định khả năng xếp hạng điểm nóng. Với dữ liệu nhiều số 0, việc thêm quá nhiều lag dễ làm mô hình bám quán tính và giảm khả năng phát hiện đỉnh mới.

**Phân tích lỗi theo mức độ ca bệnh**
Sai số thường nhỏ ở vùng y=0 và tăng mạnh khi y lớn. Để minh họa, có thể phân nhóm theo khoảng y (0, 1–2, 3–5, >5) và tính MAE theo nhóm. Điều này giúp đánh giá “tính công bằng” theo mức độ rủi ro, tránh mô hình chỉ tối ưu vùng phổ biến (y=0) mà bỏ qua các đỉnh hiếm.

**Ổn định theo mùa và khả năng tổng quát**
Backtest theo tháng (năm 2025) cho thấy sai số và Hit@K biến động theo mùa. Mùa mưa thường có sai số cao hơn do biến động lớn và đỉnh bùng phát. Điều này gợi ý cần cập nhật mô hình định kỳ và theo dõi drift.

Mô hình được huấn luyện trên Tiền Giang (cũ). Khi chuyển sang tỉnh khác, cần chuẩn hóa dữ liệu ca bệnh, cập nhật ranh giới hành chính và kiểm tra tính phù hợp của đặc trưng thời tiết (độ trễ, mùa vụ). Việc giữ nguyên pipeline nhưng thay dữ liệu giúp mở rộng phạm vi ứng dụng.

**So sánh tuyến tính vs phi tuyến và ý nghĩa Hit@K**
Linear Regression cung cấp baseline tuyến tính nhưng thiếu ràng buộc dữ liệu đếm, trong khi Poisson GLM ràng buộc theo log-link và không âm. HistGradientBoosting bắt tương tác phi tuyến tốt hơn ở vùng đỉnh. So sánh ba mô hình giúp tách bạch hiệu quả của (i) ràng buộc phân phối đếm và (ii) khả năng học phi tuyến.

Hit@K đo xác suất mô hình “bắt đúng” xã có ca cao nhất trong top-K dự báo. Đây là metric gần với mục tiêu vận hành hơn MAE vì hệ thống thực tế ưu tiên khoanh vùng can thiệp. Do đó, mô hình có MAE thấp hơn nhưng Hit@K kém hơn vẫn có thể kém hữu ích trong vận hành.

**Khía cạnh đạo đức và quản trị dữ liệu**
Dữ liệu ca bệnh có thể chứa yếu tố nhạy cảm. Hệ thống chỉ dùng dữ liệu tổng hợp theo ngày–xã nên giảm nguy cơ lộ thông tin cá nhân. Khi triển khai thực tế, cần quy trình quản trị dữ liệu, kiểm soát truy cập và tuân thủ quy định địa phương.

---

### 7.5. Phụ lục kỹ thuật và dữ liệu

**Pipeline xử lý dữ liệu và pseudocode**
Pipeline dữ liệu gồm các bước: (i) chuẩn hóa tên xã/phường, (ii) chuẩn hóa ngày tháng, (iii) tổng hợp ca bệnh theo ngày–xã, (iv) ghép với thời tiết theo ngày–xã, (v) tạo đặc trưng lag/rolling, (vi) tách train/test theo thời gian, (vii) huấn luyện và lưu artifacts.

```text
Input: cases.csv, weather.csv
1. Load cases, standardize commune names
2. Aggregate cases by (xa, date)
3. Load weather and filter communes
4. Merge weather + cases -> panel
5. Build features: lag, rolling, calendar
6. Split by date -> train/test
7. Train 3 models: Linear, Poisson GLM, HistGB
8. Evaluate metrics + save artifacts
```

**Feature store, cửa sổ lịch sử và nhất quán suy luận**
Trong suy luận, hệ thống dựng lại cửa sổ 14 ngày cho mỗi xã để tính lag/rolling. Nguồn thời tiết lấy từ feature store (lịch sử) hoặc forecast (Open‑Meteo) tùy theo ngày mục tiêu. Điều này đảm bảo tính nhất quán giữa train và inference.

**Siêu tham số và cấu hình mô hình**
- **PoissonRegressor:** alpha=1e-4, max_iter=1000.  
- **HistGradientBoostingRegressor:** loss='poisson', learning_rate=0.05, max_iter=200, max_leaf_nodes=31, min_samples_leaf=50.  
- **LinearRegression:** mặc định scikit-learn, dự báo được clip về 0.

**Kiến trúc hệ thống và luồng dữ liệu**
- **Backend:** FastAPI, phục vụ các endpoint dự báo và trả kết quả JSON.  
- **Frontend:** Leaflet + bảng Top‑K + bản đồ điểm.  
- **Tầng dữ liệu:** artifacts (model + feature store) và cache thời tiết.

**Hiệu năng, độ trễ và tối ưu**
Độ trễ suy luận phụ thuộc vào việc lấy thời tiết forecast. Khi cache được bật, phần lớn truy vấn có thể trả kết quả nhanh (dưới vài giây). Việc dựng cửa sổ 14 ngày được tối ưu bằng feature store để tránh truy vấn lặp.

---


**Từ điển dữ liệu (ca bệnh + thời tiết)**
**Ca bệnh:**
| Cột | Mô tả | Ghi chú |
|---|---|---|
| XA_PHUONG_MOI | Tên xã/phường chuẩn hóa | Đồng bộ với TenXa |
| NGAY_KHAM | Ngày khám/phát hiện | Dùng làm proxy thời điểm ca bệnh |

**Thời tiết:**
| Cột | Mô tả | Đơn vị |
|---|---|---|
| date | Ngày | YYYY‑MM‑DD |
| rain_mm | Lượng mưa | mm |
| temp_c | Nhiệt độ | °C |
| wind_speed_ms | Tốc độ gió | m/s |
| wind_dir_deg | Hướng gió | độ |

**Danh sách đặc trưng và công thức**
- Lag mưa: rain(t‑1), rain(t‑3), rain(t‑7), rain(t‑14)
- Rolling mưa: mean rain(t‑7), mean rain(t‑14)
- Lag nhiệt: temp(t‑1), temp(t‑3), temp(t‑7), temp(t‑14)
- Rolling nhiệt: mean temp(t‑7), mean temp(t‑14)
- Lịch: month, day‑of‑year, sin/cos chu kỳ
- Lag ca bệnh: cases(t‑1), cases(t‑3), cases(t‑7), cases(t‑14)

- MAE, RMSE, Poisson deviance và Hit@K như đã nêu ở Chương 4.  
- Hit@K được tính theo ngày, kiểm tra xã có ca cao nhất nằm trong top‑K dự báo.

**Tham số mô hình và cấu hình chạy**
- Cấu hình các mô hình: xem mục 7.5.  
- Cửa sổ lịch sử: 14 ngày.  
- Lag/rolling như phần danh sách đặc trưng.

**Checklist tái tạo kết quả**
1. Chạy train.py để sinh artifacts.  
2. Chạy make_figures_and_metrics_v2.py để sinh hình/metrics.  
3. Đồng bộ bảng kết quả vào báo cáo và slide.

**Gợi ý mở rộng**
- Thêm mô hình count nâng cao (NB/Zero‑Inflated).  
- Mở rộng backtest theo quý/năm.  
- Bổ sung biến xã hội và can thiệp.

---
## PHỤ LỤC A: Danh sách hình đã sinh (thư mục `report_figures/`)

| Hình | Tên file | Nội dung / mục đích |
|---:|---|---|
| 1 | `fig_cases_monthly.png` | Tổng số ca SXH theo tháng |
| 2 | `fig_rain_vs_cases_monthly.png` | So sánh xu hướng ca SXH và lượng mưa trung bình theo tháng |
| 3 | `fig_lag_corr_rain.png` | Tương quan độ trễ: $y(t)$ và $rain(t-k)$ |
| 4 | `fig_y_distribution.png` | Phân phối nhãn $y$ (log-count) và tỷ lệ $y=0$ |
| 5 | `fig_zero_rate_monthly.png` | Tỷ lệ quan sát $y=0$ theo tháng |
| 6 | `fig_mean_variance_by_xa.png` | Kiểm tra over‑dispersion: Var(y) vs Mean(y) theo xã |
| 7 | `fig_compare_baseline_vs_updated.png` | So sánh baseline vs updated theo các metrics |
| 8 | `fig_pred_vs_true.png` | Scatter $y$ thật vs $\hat{y}$ dự đoán (HistGB Poisson) trên tập test |
| 9 | `fig_feature_importance.png` | Top 20 Feature Importance (Permutation Importance, scoring = -MAE) |
| 10 | `fig_province_daily_true_vs_pred.png` | Toàn tỉnh (test): $y$ thật vs $y$ dự đoán theo ngày (GLM vs HistGB) |
| 11 | `fig_calibration_mean_y_vs_pred.png` | Calibration curve (bin theo ŷ): mean(y) vs mean(ŷ) |
| 12 | `fig_glm_residuals_vs_fitted.png` | GLM: Deviance residuals vs fitted (test) |
| 13 | `fig_hit10_over_time.png` | Hotspot Hit@10 theo ngày (test) |
| 14 | `fig_backtest_mae_by_month.png` | Backtest 2025: MAE theo tháng |
| 15 | `fig_backtest_hit10_by_month.png` | Backtest 2025: Hit@10 theo tháng |

*Ghi chú:* `fig_compare_baseline_vs_updated.png` (Hình 7) chỉ được tạo khi có file `artifacts/compare_baseline_vs_update.csv`.


## PHỤ LỤC: Cách tái tạo hình và số liệu
Chạy lệnh:

```bash
python make_figures_and_metrics_v2.py \
  --cases_csv sxh_sap_nhap_mapped.csv \
  --weather_csv weather_daily_2023_2025.csv \
  --out_dir report_figures
```

Các file kết quả sẽ nằm trong `report_figures/`.

---

## Tài liệu tham khảo

[1] World Health Organization (WHO), “Dengue and severe dengue.” [Online]. Available: https://www.who.int/news-room/fact-sheets/detail/dengue-and-severe-dengue  
[2] World Health Organization and TDR, “Dengue early warning system (EWS).” [Online]. Available: https://www.who.int/tdr/publications/documents/dengue-ews.pdf  
[3] C. W. Morin, A. C. Comrie, and K. Ernst, “Climate and dengue transmission: evidence and implications,” *Nature Climate Change*, 2013. doi: 10.1038/nclimate1826  
[4] M. A. Johansson et al., “Climate variability and dengue hemorrhagic fever in Thailand,” 2009. doi: 10.1016/j.epidem.2009.01.002  
[5] R. Lowe et al., “Dengue outlook for the World Cup in Brazil: weather-based forecast,” 2011. doi: 10.1371/journal.pntd.0001038  
[6] P. McCullagh and J. A. Nelder, *Generalized Linear Models*, 2nd ed. Chapman & Hall, 1989. [Online]. Available: https://www.taylorfrancis.com/books/mono/10.1201/9780203753736  
[7] A. C. Cameron and P. K. Trivedi, *Regression Analysis of Count Data*, 2nd ed. Cambridge Univ. Press, 2013. doi: 10.1017/CBO9781139013567  
[8] J. M. Hilbe, *Modeling Count Data*. Cambridge Univ. Press, 2014. doi: 10.1017/CBO9781139236065  
[9] J. A. Nelder and R. W. M. Wedderburn, “Generalized linear models,” 1972. doi: 10.2307/2344614  
[10] J. H. Friedman, “Greedy function approximation: A gradient boosting machine,” 2001. [Online]. Available: https://statweb.stanford.edu/~jhf/ftp/trebst.pdf  
[11] J. H. Friedman, “Stochastic gradient boosting,” 2002. doi: 10.1198/016214502388618476  
[12] G. Ridgeway, “Generalized boosted models: A guide to the gbm package,” 2007. [Online]. Available: https://cran.r-project.org/web/packages/gbm/vignettes/gbm.pdf  
[13] P. Bühlmann and T. Hothorn, “Boosting algorithms: Regularization, prediction and model fitting,” 2007. doi: 10.1214/07-STS242  
[14] G. Ke et al., “LightGBM: A highly efficient gradient boosting decision tree,” 2017. [Online]. Available: https://papers.nips.cc/paper/2017/hash/6449f44a102fde848669bdd9eb6b76fa-Abstract.html  
[15] scikit-learn documentation, “PoissonRegressor.” [Online]. Available: https://scikit-learn.org/stable/modules/generated/sklearn.linear_model.PoissonRegressor.html  
[16] scikit-learn documentation, “HistGradientBoostingRegressor (loss='poisson').” [Online]. Available: https://scikit-learn.org/stable/modules/ensemble.html#histogram-based-gradient-boosting; https://scikit-learn.org/stable/modules/generated/sklearn.ensemble.HistGradientBoostingRegressor.html  
[17] Open-Meteo, “Historical & Forecast Weather API.” [Online]. Available: https://open-meteo.com/en/docs