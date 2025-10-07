import type { User } from "../entities/Auth";

export interface ITokenStorage {
  saveToken(token: string): void;
  getToken(): string | null;
  clear(): void;

  saveUser(user: User): void;
  getUser(): User | null;
}