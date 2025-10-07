// infrastructure/storage/TokenStorage.ts
import type { ITokenStorage } from "../../domain/repositories/ITokenStorage";
import type { User } from "../../domain/entities/Auth";

export class TokenStorage implements ITokenStorage {
  private readonly TOKEN_KEY = "authToken";
  private readonly USER_KEY = "authUser";
  private readonly USER_EMAIL = "userEmail";
  private readonly USER_ID = "userId";
  private readonly USER_ROLE = "userRole";

  saveToken(token: string): void {
    localStorage.setItem(this.TOKEN_KEY, token);
  }

  getToken(): string | null {
    return localStorage.getItem(this.TOKEN_KEY);
  }

  clear(): void {
    localStorage.removeItem(this.TOKEN_KEY);
    localStorage.removeItem(this.USER_KEY);
    localStorage.removeItem(this.USER_EMAIL);
    localStorage.removeItem(this.USER_ID);
    localStorage.removeItem(this.USER_ROLE);
  }

  saveUser(user: User): void {
    localStorage.setItem(this.USER_KEY, JSON.stringify(user));
  }

  getUser(): User | null {
    const data = localStorage.getItem(this.USER_KEY);
    return data ? (JSON.parse(data) as User) : null;
  }
}