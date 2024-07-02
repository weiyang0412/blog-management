export function isLoggedIn() {
    const loggedInUser = localStorage.getItem('loggedInUser');
    return !!loggedInUser;
}