const input = document.querySelector('#adminSearch');
const searchableRows = [...document.querySelectorAll('.searchable')];

if (input) {
  input.addEventListener('input', () => {
    const term = input.value.trim().toLowerCase();
    searchableRows.forEach((row) => {
      row.classList.toggle('is-hidden-by-search', term && !row.textContent.toLowerCase().includes(term));
    });
  });
}
