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
};

/**
 * Err モジュールストア
 */
export const ERR = {
  // Module
  STORE: 'Err',

  // Getters
  GET_CODE: 'getCode',

  // Mutations
  SET_CODE: 'setCode',

  // Actions
  SET_CODE: 'setCode',

  // Status code
  OK: 200,
  CREATED: 201,
  INTERNAL_SERVER_ERROR: 500,
};
