
const fromDateInput = document.getElementById('fromDate'),
      toDateInput = document.getElementById('toDate'),
      todayDate = new Date().toISOString().split('T')[0],
      mockfromDate = document.querySelector('[data-mock-date="fromDate"]'),
      mocktoDate = document.querySelector('[data-mock-date="toDate"]')

fromDateInput.max = todayDate;
toDateInput.max = todayDate;

fromDateInput.addEventListener('change', handleFromDateChange);

function handleFromDateChange() {
    const fromDate = new Date(fromDateInput.value);

    if (fromDate > new Date(toDateInput.value) || fromDate > new Date()) {
        fromDateInput.value = toDateInput.value;
    }

    if (fromDateInput.value) {
        mockfromDate.innerHTML = 'Data nuo: ' + fromDateInput.value
    } else {
        mockfromDate.innerHTML = 'Data nuo'
    }

    fromDateInput.max = toDateInput.value;
    toDateInput.min = fromDateInput.value;
}

toDateInput.addEventListener('change', handleToDateChange);

function handleToDateChange() {
    const toDate = new Date(toDateInput.value);

    if (toDate < new Date(fromDateInput.value) || toDate > new Date()) {
        toDateInput.value = fromDateInput.value;
    }

    if (toDateInput.value) {
        mocktoDate.innerHTML = 'Data iki: ' + toDateInput.value
    } else {
        mocktoDate.innerHTML = 'Data iki'
    }

    fromDateInput.max = toDateInput.value
    toDateInput.min = fromDateInput.value;
}
