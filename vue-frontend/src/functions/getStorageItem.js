export const getStorageItem = (key) => {
  if (localStorage.getItem(key)) {
    return localStorage.getItem(key)
  } else {
    return sessionStorage.getItem(key)
  }
}
