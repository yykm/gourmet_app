/**
 * App モジュールストア
 */
export const APP = {
  // Module
  STORE: 'App',

  // Getters
  GET_SHOPS: 'getShops',
  GET_API_STATUS: 'getApiStatus',
  IS_LOGIN: 'isLogin',
  USER_NAME: 'userName',
  SHOPS_COUNT: 'shopsCount',
  GET_SHOPS_BY_PAGE: 'getShopsByPage',
  GET_URLS: 'getURLs',

  // Mutations
  UPDATE_SHOPS: 'updateShops',
  SET_USER: 'setUser',
  SET_API_STATUS:'setApiStatus',

  // Actions
  UPDATE_SHOPS: 'updateShops',
  REGISTER: 'register',
  LOGIN: 'login',
  LOGOUT: 'logout',
  CURRET_USER: 'currentUser',

  getAppURI: function(value){
    return (this.STORE + '/' + value);
  }
};

/**
 * Err モジュールストア
 */
export const ERR = {
  // Module
  STORE: 'Err',

  // Getters
  GET_CODE: 'getCode',
  GET_ERROR_MESSAGE: 'getErrorMessage',

  // Mutations
  SET_CODE: 'setCode',
  SET_ERROR_MESSAGE: 'setErrorMessage',

  // Actions
  SET_CODE: 'setCode',

  // Status code
  OK: 200,
  CREATED: 201,
  UNPROCESSABLE_ENTITY: 422,
  INTERNAL_SERVER_ERROR: 500,

  // Error Message
  MSG_UNPROCESSABLE_ENTITY: "ログインID、またはパスワードが間違っています。",

  getErrURI: function(value){
    return (this.STORE + '/' + value);
  }
};