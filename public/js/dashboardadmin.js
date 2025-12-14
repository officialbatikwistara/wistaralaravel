document.addEventListener("DOMContentLoaded", function () {
    const chartElement = document.querySelector("#sales-chart");
    const backButton = document.querySelector("#sales-back-btn");

    if (!chartElement || !backButton) {
        return;
    }

    const currencyFormatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    });

    let analyticsData = null;
    let currentLevel = "summary";
    let productList = [];
    let productLabels = [];
    let quarterSlices = [];
    let currentQuarterIndex = 0;
    let visibleSummaryItems = [];

    const chart = new ApexCharts(chartElement, {
        chart: {
            type: "bar",
            height: 400,
            toolbar: { show: false },
            animations: {
                enabled: true,
                easing: "easeinout",
                speed: 1000,
                animateGradually: { enabled: true, delay: 150 },
                dynamicAnimation: { enabled: true, speed: 400 },
            },
            events: {
                dataPointSelection(event, chartContext, config) {
                    if (
                        !visibleSummaryItems.length ||
                        currentLevel !== "summary"
                    ) {
                        return;
                    }

                    const clickedIndex = config.dataPointIndex;
                    const selected = visibleSummaryItems[clickedIndex];

                    if (selected) {
                        renderDetail(selected.key, selected.label);
                    }
                },
            },
        },
        colors: ["#d4af37"],
        plotOptions: {
            bar: {
                horizontal: true, // animasi kiri -> kanan
                barHeight: "55%",
                borderRadius: 8,
            },
        },
        series: [],
        xaxis: {
            categories: [],
            labels: {
                style: { fontSize: "12px" },
            },
        },
        yaxis: {
            labels: {
                style: { fontSize: "12px" },
            },
        },
        dataLabels: {
            enabled: true,
            formatter: (value) => currencyFormatter.format(value),
        },
        tooltip: {
            y: {
                formatter: (value) => currencyFormatter.format(value),
            },
        },
        noData: {
            text: "Memuat data penjualan...",
            align: "center",
            style: {
                fontSize: "14px",
                color: "#071739",
            },
        },
    });

    chart.render();

    const toValidNumber = (value) => {
        const numericValue =
            typeof value === "number" ? value : parseFloat(value);
        return Number.isFinite(numericValue) ? numericValue : 0;
    };

    const applyCurrencyFormatting = (
        maxValue = 0,
        categories = null,
        labelCssClass = ""
    ) => {
        // Tentukan step dinamis supaya axis tidak terlalu rapat untuk nilai besar
        const niceSteps = [
            100_000, 250_000, 500_000, 1_000_000, 2_500_000, 5_000_000,
            10_000_000,
        ];
        const targetMax = Math.max(toValidNumber(maxValue), 100_000);
        const pickedStep =
            niceSteps.find((s) => targetMax / s <= 8) || 10_000_000;
        const safeMax = Math.ceil(targetMax / pickedStep) * pickedStep;
        const tickAmount = Math.max(Math.round(safeMax / pickedStep), 2);

        let customFormatter = (value, index) =>
            currencyFormatter.format(toValidNumber(value));

        if (Array.isArray(categories)) {
            const labelCount = categories.length;
            const viewportWidth =
                window.innerWidth ||
                document.documentElement.clientWidth ||
                document.body.clientWidth;

            if (viewportWidth && viewportWidth < 400 && labelCount > 6) {
                customFormatter = (value, index) => {
                    const displayIndex = index ?? categories.indexOf(value);
                    if (
                        displayIndex === 0 ||
                        displayIndex === labelCount - 1 ||
                        displayIndex % 3 === 0
                    ) {
                        return currencyFormatter.format(toValidNumber(value));
                    }
                    return "";
                };
            }
        }

        const xaxisConfig = {
            min: 0,
            max: safeMax,
            tickAmount,
            labels: {
                formatter: customFormatter,
                style: {
                    fontSize: "12px",
                    ...(labelCssClass ? { cssClass: labelCssClass } : {}),
                },
            },
        };

        if (Array.isArray(categories)) {
            xaxisConfig.categories = categories;
        }

        chart.updateOptions(
            {
                xaxis: xaxisConfig,
                dataLabels: {
                    enabled: true,
                    formatter: (value) =>
                        currencyFormatter.format(toValidNumber(value)),
                },
                tooltip: {
                    y: {
                        formatter: (value) =>
                            currencyFormatter.format(toValidNumber(value)),
                    },
                },
            },
            false,
            true
        );
    };

    const applyQuantityFormatting = (
        maxValue = 0,
        categories = null,
        labelCssClass = ""
    ) => {
        const step = 1;
        const targetMax = Math.max(toValidNumber(maxValue), step);
        const safeMax = Math.max(10, Math.ceil(targetMax / step) * step);
        const tickAmount = Math.max(Math.round(safeMax / step), 1);

        let customFormatter = (value, index) => `${toValidNumber(value)}`;

        if (Array.isArray(categories)) {
            const labelCount = categories.length;
            const viewportWidth =
                window.innerWidth ||
                document.documentElement.clientWidth ||
                document.body.clientWidth;

            if (viewportWidth && viewportWidth < 400 && labelCount > 6) {
                customFormatter = (value, index) => {
                    if (
                        index === 0 ||
                        index === labelCount - 1 ||
                        index % 3 === 0
                    ) {
                        return `${toValidNumber(value)}`;
                    }
                    return "";
                };
            }
        }

        const xaxisConfig = {
            min: 0,
            max: safeMax,
            tickAmount,
            labels: {
                formatter: customFormatter,
                style: {
                    fontSize: "12px",
                    ...(labelCssClass ? { cssClass: labelCssClass } : {}),
                },
            },
        };

        if (Array.isArray(categories)) {
            xaxisConfig.categories = categories;
        }

        chart.updateOptions(
            {
                xaxis: xaxisConfig,
                dataLabels: {
                    enabled: true,
                    formatter: (value) => `${toValidNumber(value)}`,
                },
                tooltip: {
                    y: {
                        formatter: (value) => `${toValidNumber(value)} pcs`,
                    },
                },
            },
            false,
            true
        );
    };

    const quarterPrevBtn = document.querySelector("#quarter-prev-btn");
    const quarterNextBtn = document.querySelector("#quarter-next-btn");
    const quarterLabel = document.querySelector("#quarter-label");
    const quarterNav = document.querySelector(".quarter-nav");
    const summaryCurrencyLabelClass = "apexcharts-currency-vertical";

    const wrapCategoryLabel = (label = "", maxLength = 18) => {
        if (typeof label !== "string") {
            return "";
        }

        const normalized = label.trim();
        if (normalized.length <= maxLength) {
            return normalized;
        }

        const words = normalized.split(" ");
        const lines = [];
        let currentLine = "";

        words.forEach((word) => {
            const tentative = currentLine ? `${currentLine} ${word}` : word;
            if (tentative.length > maxLength) {
                if (currentLine) {
                    lines.push(currentLine);
                }

                if (word.length > maxLength) {
                    for (let i = 0; i < word.length; i += maxLength) {
                        lines.push(word.slice(i, i + maxLength));
                    }
                    currentLine = "";
                } else {
                    currentLine = word;
                }
            } else {
                currentLine = tentative;
            }
        });

        if (currentLine) {
            lines.push(currentLine);
        }

        return lines.join("\n");
    };

    function prepareQuarterSlices(summary = []) {
        if (!Array.isArray(summary) || !summary.length) {
            return [];
        }

        const sorted = [...summary].sort((a, b) => {
            return new Date(a.key + "-01") - new Date(b.key + "-01");
        });

        const slices = [];
        for (let i = 0; i < sorted.length; i += 3) {
            const chunk = sorted.slice(i, i + 3);
            if (!chunk.length) continue;
            const quarterLabel = buildQuarterLabel(chunk[0]);
            slices.push({
                label: quarterLabel,
                items: chunk,
            });
        }
        return slices;
    }

    function buildQuarterLabel(item) {
        if (!item || !item.key) return "-";
        const [year, month] = item.key.split("-");
        const quarter = Math.floor((parseInt(month, 10) - 1) / 3) + 1;
        return `Q${quarter} ${year}`;
    }

    function updateQuarterNav() {
        if (!quarterSlices.length) {
            quarterLabel.textContent = "-";
            quarterPrevBtn.disabled = true;
            quarterNextBtn.disabled = true;
            return;
        }
        quarterLabel.textContent =
            quarterSlices[currentQuarterIndex]?.label ?? "-";
        quarterPrevBtn.disabled = currentQuarterIndex === 0;
        quarterNextBtn.disabled =
            quarterSlices.length &&
            currentQuarterIndex === quarterSlices.length - 1;
    }

    fetch(window.APP_CONFIG.salesDataUrl)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Gagal memuat data penjualan");
            }
            return response.json();
        })
        .then((data) => {
            analyticsData = data;
            productList = Array.isArray(data.products) ? data.products : [];
            productLabels = productList.map((product) => product.name);
            quarterSlices = prepareQuarterSlices(data.summary || []);
            currentQuarterIndex = quarterSlices.length
                ? quarterSlices.length - 1
                : 0;
            renderSummary();
        })
        .catch((error) => {
            console.error(error);
            chart.updateOptions({
                noData: {
                    text: error.message || "Data penjualan gagal dimuat",
                },
            });
        });

    backButton.addEventListener("click", () => {
        renderSummary();
    });

    quarterPrevBtn.addEventListener("click", () => {
        if (currentQuarterIndex > 0) {
            currentQuarterIndex -= 1;
            renderSummary();
        }
    });

    quarterNextBtn.addEventListener("click", () => {
        if (currentQuarterIndex < quarterSlices.length - 1) {
            currentQuarterIndex += 1;
            renderSummary();
        }
    });

    function renderSummary() {
        if (!quarterSlices.length) {
            chart.updateSeries([]);
            chart.updateOptions({
                noData: { text: "Belum ada data penjualan" },
            });
            visibleSummaryItems = [];
            applyCurrencyFormatting(0, [], summaryCurrencyLabelClass);
            updateQuarterNav();
            return;
        }

        currentLevel = "summary";
        backButton.hidden = true;
        if (quarterNav) {
            quarterNav.hidden = false;
        }

        const activeSlice = quarterSlices[currentQuarterIndex] || { items: [] };
        visibleSummaryItems = activeSlice.items;

        const labels = activeSlice.items.map((item) => item.label);
        const totals = activeSlice.items.map((item) => item.total);
        const maxTotal = totals.reduce((acc, value) => Math.max(acc, value), 0);

        chart.updateOptions({
            title: {
                text: "Total Penjualan Bulanan",
                style: { fontSize: "18px" },
            },
            subtitle: {
                text: "Klik batang bulan tertentu untuk melihat penjualan per produk",
                style: { fontSize: "13px" },
            },
        });

        chart.updateSeries([
            {
                name: "Total Penjualan",
                data: totals,
            },
        ]);

        applyCurrencyFormatting(maxTotal, labels, summaryCurrencyLabelClass);
        updateQuarterNav();
    }

    function renderDetail(monthKey, label) {
        currentLevel = "detail";
        backButton.hidden = false;
        if (quarterNav) {
            quarterNav.hidden = true;
        }

        const detail =
            analyticsData &&
            analyticsData.details &&
            analyticsData.details[monthKey]
                ? analyticsData.details[monthKey]
                : [];

        if (!detail.length) {
            applyQuantityFormatting(0, []);
            chart.updateSeries([]);
            chart.updateOptions({
                title: { text: `Detail Penjualan (${label})` },
                subtitle: { text: "Belum ada data produk pada bulan ini" },
                noData: { text: "Detail produk belum tersedia" },
            });
            return;
        }

        const detailCategories = detail.map((item) =>
            wrapCategoryLabel(item.name)
        );

        chart.updateOptions({
            title: { text: `Penjualan per Produk (${label})` },
            subtitle: { text: 'Tekan "Level Bulanan" untuk kembali' },
        });

        chart.updateSeries([
            {
                name: "Qty Terjual",
                data: detail.map((item) => item.qty),
            },
        ]);

        const maxQty = detail.reduce((acc, item) => Math.max(acc, item.qty), 0);
        applyQuantityFormatting(maxQty, detailCategories);
    }
});
