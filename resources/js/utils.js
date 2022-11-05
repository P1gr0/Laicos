export function timeSince(date) {
    let seconds = Math.floor((new Date() - date) / 1000);
    let interval = seconds / 31536000;

    if (interval > 1) return date.toLocaleDateString([], { year: 'numeric', month: 'long', day: 'numeric' });
    interval = seconds / 2592000;
    if (interval > 1) return date.toLocaleDateString([], { month: 'long', day: 'numeric' });;
    interval = seconds / 86400;

    let time = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    if (interval > 1)
        return Math.floor(interval) + "d ago at " + time;
    interval = seconds / 3600;
    if (interval > 1) return 'Today at ' + time;
    interval = seconds / 60;
    if (interval > 1) return Math.floor(interval) + "min ago";
    return Math.floor(seconds) + "s ago";
}

export function linkify(text) {
    let urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    return text.replace(urlRegex, function (url) {
        return `<a target="_blank" href="${url}">${url}</a>`;
    });
}

export function dataURLtoFile(dataurl, filename) {
    var arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);

    while (n--)
        u8arr[n] = bstr.charCodeAt(n);
        
    return new File([u8arr], filename, { type: mime });
}

