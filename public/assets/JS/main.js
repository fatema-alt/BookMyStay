console.log('BookMyStay assets loaded');

const checkIn = document.querySelector('input[name="check_in"]');
const checkOut = document.querySelector('input[name="check_out"]');

if (checkIn && checkOut) {
    checkIn.addEventListener('change', function () {
        checkOut.min = this.value;
    });
}
function initRevenueChart(months, revenues) {
    const ctx = document.getElementById('revenueChart');

    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue',
                data: revenues,
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]
        }
    });
}