<?php
namespace App\Models\Objects;

use App\Models\Lists\ListPage;
use App\Models\Objects\DatabaseObject;
use DateTime;

class User extends DatabaseObject {
	private int|null $id;
	private string|null $email;
	private string|null $password;
	private string|null $role;
	private DateTime|null $CreatedAt;

	private ListPage|null $Pages;


	#region Getters
	public function GetId(): int|null {
		return $this->id;
	}

	public function GetEmail(): string|null {
		return $this->email;
	}

	public function GetPassword(): string|null {
		return $this->password;
	}

	public function GetRole(): string|null {
		return $this->role;
	}

	public function GetCreatedAt(): DateTime|null {
		return $this->CreatedAt;
	}

	public function GetCreatedAtAsString(): string|null {
		if ($this->CreatedAt === null) {
			return null;
		}

		return $this->CreatedAt->format("d M Y");
	}

	public function GetPages(): ListPage|null {
		return $this->Pages;
	}
	#endregion


	#region Getters
	public function SetId(int|null $id): void {
		$this->id = $id;
	}

	public function SetEmail(string|null $email): void {
		$this->email = $email;
	}

	public function SetPassword(string|null $password): void {
		$this->password = $password;
	}

	public function SetRole(string|null $role): void {
		$this->role = $role;
	}

	public function SetCreatedAt(DateTime|null $CreatedAt): void {
		$this->CreatedAt = $CreatedAt;
	}

	public function SetCreatedAtFromString(string|null $created_at): void {
		if ($created_at === null) {
			$this->CreatedAt = null;
			return;
		}

		$this->CreatedAt = new DateTime($created_at);
	}

	public function SetPages(ListPage|null $Pages): void {
		$this->Pages = $Pages ?? new ListPage;
	}
	#endregion


	public function __construct(
		int|null $id = null, 
		string|null $email = null, 
		string|null $password = null, 
		string|null $role = null, 
		DateTime|null $CreatedAt = null
	) {
		$this->id = $id;
		$this->email = $email;
		$this->password = $password;
		$this->role = $role;
		$this->CreatedAt = $CreatedAt;
	}


	public static function NewObject(array $data = []): self|null {
		if ($data === []) {
			return null;
		}

		$instance = new self;
		
		$instance->SetId($data["id"]);
		$instance->SetEmail($data["email"]);
		$instance->SetPassword($data["password"]);
		$instance->SetRole($data["role"]);
		$instance->SetCreatedAtFromString($data["created_at"]);
		
		return $instance;
	}
}