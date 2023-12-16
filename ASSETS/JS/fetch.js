const fetchHandler = async (url, fetchData = null) => {
    return fetch(url, fetchData)
        .then(async res => {
            if (res.ok) return res.json()
            else return res.json().then(ex => {
                throw ex
            })
        })
}

const getRequestData = (method, jwt, body) => {
    return {
        method,
        headers: getHeaders(jwt),
        body
    }
}

const getHeaders = jwt => {
    if (jwt) {
        return {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${jwt}`
        }
    } else {
        return {
            'Content-Type': 'application/json'
        }
    }
}
export {
    fetchHandler,
    getRequestData
}