export function request(method, url, data = {}) {
    return fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
        },
        ...(method === 'get' ? {} : { body: JSON.stringify(data) })
    }).then(async (response) => {
        if (response.status >= 200 && response.status < 300) {
            return response.json()
        }
        throw await response.json()
    })
}

// The spread operator (...) is used to conditionally include the body property in the request object. If the method is get, the body property is not included (since GET requests do not have a request body). Otherwise, the data object is converted to a JSON string using JSON.stringify and assigned to the body property.

export function get(url) {
    return request('get', url)
}

export function post(url, data) {
    return request('post', url, data)
}