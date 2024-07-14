export function isLoggedIn() {
    const loggedInUser = localStorage.getItem('loggedInUser');
    return !!loggedInUser;
}

export function getLoggedInUser() {
    const loggedInUser = localStorage.getItem('loggedInUser');
    return JSON.parse(loggedInUser);
}