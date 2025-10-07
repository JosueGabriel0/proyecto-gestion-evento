import type { IAuthRepository } from "../../../domain/repositories/IAuthRepository";

export class ValidateTokenUseCase {
    private readonly authRepository: IAuthRepository;
  constructor(authRepository: IAuthRepository) {
    this.authRepository = authRepository;
  }

  async execute(token: string): Promise<boolean> {
    return await this.authRepository.validateToken(token);
  }
}