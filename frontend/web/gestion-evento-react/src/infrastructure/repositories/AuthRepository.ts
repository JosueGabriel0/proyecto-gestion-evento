import axios from "axios";
import type {
  LoginCredentials,
  LoginResponse,
} from "../../domain/entities/Auth";
import type { IAuthRepository } from "../../domain/repositories/IAuthRepository";

const baseUrl = import.meta.env.VITE_API_BASE_URL;

export class AuthRepository implements IAuthRepository {
  async login(credentials: LoginCredentials): Promise<LoginResponse> {
    const { data } = await axios.post<LoginResponse>(
      `${baseUrl}/api/login`,
      credentials,
      {
        headers: { "Content-Type": "application/json" },
      }
    );
    return data;
  }

  async validateToken(token: string): Promise<boolean> {
    try {
      await axios.post(
        `${baseUrl}/auth/validate`,
        { token },
        { headers: { "Content-Type": "application/json" } }
      );
      return true;
    } catch {
      return false;
    }
  }
}