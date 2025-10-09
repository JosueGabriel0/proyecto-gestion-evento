// src/services/authServiceInstance.ts
import { AuthService } from "../../application/services/AuthService";
import { AuthRepository } from "../../infrastructure/repositories/AuthRepository";
import { TokenStorage } from "../../infrastructure/repositories/TokenStorage";

export const authService = new AuthService(
  new AuthRepository(),
  new TokenStorage()
);