// application/services/AuthService.ts
import type { IAuthRepository } from "../../domain/repositories/IAuthRepository";
import type { ITokenStorage } from "../../domain/repositories/ITokenStorage";

import { LoginUseCase } from "../useCases/auth/LoginUseCase";
import { LogoutUseCase } from "../useCases/auth/LogoutUseCase";
import { ValidateTokenUseCase } from "../useCases/auth/ValidateTokenUseCase";
import { GetUserUseCase } from "../useCases/auth/GetUserUseCase";
import type { User } from "../../domain/entities/Auth";

export class AuthService {
  private loginUC: LoginUseCase;
  private logoutUC: LogoutUseCase;
  private validateUC: ValidateTokenUseCase;
  private getUserUC: GetUserUseCase;
  private tokenStorage: ITokenStorage;

  constructor(authRepo: IAuthRepository, tokenStorage: ITokenStorage) {
    this.loginUC = new LoginUseCase(authRepo);
    this.logoutUC = new LogoutUseCase(tokenStorage);
    this.validateUC = new ValidateTokenUseCase(authRepo);
    this.getUserUC = new GetUserUseCase(tokenStorage);
    this.tokenStorage = tokenStorage;
  }

  async login(email: string, password: string) {
    const { token, user } = await this.loginUC.execute({ email, password });

    // 🚀 Persistir token y usuario
    this.tokenStorage.saveToken(token);
    this.tokenStorage.saveUser(user);

    return { token, user };
  }

  async logout() {
    this.logoutUC.execute();
  }

  async validate(token: string) {
    return this.validateUC.execute(token);
  }

  getUser(): User | null {
    return this.getUserUC.execute();
  }
}