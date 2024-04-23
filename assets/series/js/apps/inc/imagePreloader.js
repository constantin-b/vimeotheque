/**
 * Image preloader utility function.
 *
 * @param src
 * @returns {Promise<unknown>}
 */
export const preloadImage = ( src ) => {
    return new Promise((resolve, reject) => {
        const img = new Image()
        img.onload = function() {
            resolve(img)
        }
        img.onerror = img.onabort = function() {
            reject(src)
        }
        img.src = src
    })
}