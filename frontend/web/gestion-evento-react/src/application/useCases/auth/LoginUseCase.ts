// application/useCases/auth/LoginUseCase.ts
import type { IAuthRepository } from "../../../domain/repositories/IAuthRepository";
import type { LoginCredentials, LoginResponse } from "../../../domain/entities/Auth";

export class LoginUseCase {
    private readonly authRepository: IAuthRepository;   
  constructor(authRepository: IAuthRepository) {
    this.authRepository = authRepository;
  }

  async execute(credentials: LoginCredentials): Promise<LoginResponse> {
    return await this.authRepository.login(credentials);
  }
}