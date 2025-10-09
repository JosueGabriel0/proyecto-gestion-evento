// domain/repositories/IAuthRepository.ts
import type { LoginCredentials, LoginResponse } from "../entities/Auth";

export interface IAuthRepository {
  login(credentials: LoginCredentials): Promise<LoginResponse>;
  validateToken(token: string): Promise<boolean>;
}