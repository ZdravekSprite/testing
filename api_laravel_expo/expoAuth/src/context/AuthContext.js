import axios from 'axios';
import { createContext } from "react";
import { BASE_URL } from "../config";

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const register = (name, email, password, password_confirmation) => {
    axios
      .post(`${BASE_URL}/register`, {
        name,
        email,
        password,
        password_confirmation,
      })
      .then(res => {
        let userInfo = res.data;
        console.log(userInfo);
      })
      .catch(e => {
        console.log(`register error ${e}`);
      });
  }
  return (
    <AuthContext.Provider value={{ register }}>{children}</AuthContext.Provider>
  )
}