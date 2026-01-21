export function navigateBack(defaultRoute = "/") {
    const referrer = document.referrer
    const currentDomain = window.location.origin

    if (referrer && referrer.startsWith(currentDomain)) {
        history.back()
    } else {
        window.location.href = defaultRoute
    }
}