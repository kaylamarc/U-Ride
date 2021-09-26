/* ========================= START LOGIN MODAL SWAP ========================= */
const switchers = [...document.querySelectorAll('.switcher')]

switchers.forEach(item => {
	item.addEventListener('click', function() {
		switchers.forEach(item => item.parentElement.classList.remove('is-active'))
		this.parentElement.classList.add('is-active')
	})
})
/* ========================= END LOGIN MODAL SWAP ========================= */


// TODO: validate user input
// on press login, login
function doLogin() {
    window.location.href = "dashboard.html";
}