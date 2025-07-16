# 📌 Banco de Dados - Documentação do Esquema

> Estrutura organizada do seu sistema Kanban Multi-Tenant 🚀

---

## 👤 **users**

Usuários registrados no sistema.

| Campo               | Tipo       | Descrição                                     |
|---------------------|------------|-----------------------------------------------|
| id                  | bigint     | Chave primária                                |
| name                | string     | Nome do usuário                               |
| email               | string     | E-mail único                                  |
| email_verified_at   | timestamp  | Data de verificação do e-mail                 |
| password            | string     | Senha do usuário (hash)                       |
| remember_token      | string     | Token de sessão                               |
| timestamps          | timestamps | Data de criação e atualização                 |

---

## 🔑 **password_reset_tokens**

Gerencia tokens de redefinição de senha.

| Campo      | Tipo      | Descrição                          |
|------------|-----------|------------------------------------|
| email      | string    | E-mail primário                    |
| token      | string    | Token de redefinição               |
| created_at | timestamp | Data de criação do token           |

---

## 🗂️ **sessions**

Gerencia sessões de usuários logados.

| Campo        | Tipo     | Descrição                                   |
|--------------|----------|---------------------------------------------|
| id           | string   | Chave primária                              |
| user_id      | FK users | FK opcional para o usuário                  |
| ip_address   | string   | IP do usuário                               |
| user_agent   | text     | User agent do navegador                     |
| payload      | longText | Dados serializados da sessão                |
| last_activity| integer  | Última atividade                            |

---

## 🏢 **tenants**

Empresas/organizações do sistema.

| Campo         | Tipo       | Descrição                              |
|---------------|------------|----------------------------------------|
| id            | bigint     | Chave primária                         |
| name          | string     | Nome da empresa                        |
| slug          | string     | Slug único                             |
| domain        | string     | Domínio da empresa                     |
| logo          | string     | Logo (opcional)                        |
| color         | string     | Cor da marca                           |
| description   | string     | Descrição da empresa                   |
| is_active     | boolean    | Empresa ativa                          |
| is_verified   | boolean    | Empresa verificada                     |
| is_premium    | boolean    | Empresa premium                        |
| is_archived   | boolean    | Empresa arquivada                      |
| deleted_at    | timestamp  | Soft delete                            |
| timestamps    | timestamps | Criação e atualização                  |

---

## 👥 **tenant_user**

Pivot de relacionamento entre usuários e tenants.

| Campo    | Tipo           | Descrição                          |
|----------|----------------|------------------------------------|
| id       | bigint         | Chave primária                     |
| tenant_id| FK tenants     | Empresa                            |
| user_id  | FK users       | Usuário                            |
| role     | enum           | owner/admin/member                 |
| timestamps| timestamps    | Datas de criação/atualização       |

---

## 🧩 **boards**

Quadros Kanban.

| Campo                | Tipo           | Descrição                                    |
|----------------------|----------------|----------------------------------------------|
| id                   | bigint         | Chave primária                               |
| name                 | string         | Nome do quadro                               |
| tenant_id            | FK tenants     | Empresa dona do quadro                       |
| color                | string         | Cor do quadro                                |
| icon                 | string         | Ícone do quadro (opcional)                   |
| is_favorite          | boolean        | Indica se é favorito                         |
| user_id              | FK users       | Usuário criador do quadro                    |
| slug                 | string         | Slug único                                   |
| description          | string         | Descrição do quadro                          |
| background_image     | string         | Imagem de fundo                              |
| is_public            | boolean        | Quadro público                               |
| is_archived          | boolean        | Quadro arquivado                             |
| is_template          | boolean        | É um modelo/template?                        |
| is_read_only         | boolean        | Somente leitura                              |
| is_collaborative     | boolean        | Colaborativo                                 |
| is_private           | boolean        | Privado                                      |
| is_locked            | boolean        | Bloqueado                                    |
| is_pinned            | boolean        | Fixado                                       |
| is_hidden            | boolean        | Oculto                                       |
| is_archived_by_user  | boolean        | Arquivado pelo usuário                       |
| is_shared            | boolean        | Compartilhado                                |
| is_synced            | boolean        | Sincronizado                                 |
| is_trashed           | boolean        | Na lixeira                                   |
| deleted_at           | timestamp      | Soft delete                                  |
| timestamps           | timestamps     | Criação/atualização                          |

---

## 👥 **board_members**

Pivot de relacionamento entre usuários e quadros.

| Campo    | Tipo           | Descrição                          |
|----------|----------------|------------------------------------|
| id       | bigint         | Chave primária                     |
| board_id | FK boards      | Quadro                             |
| user_id  | FK users       | Usuário                            |
| role     | enum           | owner/member/viewer                |
| timestamps| timestamps    | Datas de criação/atualização       |

---

## 📌 **columns**

Colunas do quadro Kanban.

| Campo         | Tipo           | Descrição                          |
|---------------|----------------|------------------------------------|
| id            | bigint         | Chave primária                     |
| tenant_id     | FK tenants     | Empresa dona                       |
| board_id      | FK boards      | Quadro dono                        |
| name          | string         | Nome da coluna                     |
| color         | string         | Cor da coluna                      |
| position      | integer        | Ordem da coluna no quadro          |
| is_locked     | boolean        | Bloqueada                          |
| is_hidden     | boolean        | Oculta                             |
| slug          | string         | Slug único                         |
| description   | string         | Descrição da coluna                |
| is_archived   | boolean        | Arquivada                          |
| is_template   | boolean        | É modelo                           |
| is_read_only  | boolean        | Somente leitura                    |
| is_collaborative | boolean     | Colaborativa                       |
| deleted_at    | timestamp      | Soft delete                        |
| timestamps    | timestamps     | Criação/atualização                |

---

## ✅ **cards**

Cards/tarefas dentro das colunas.

| Campo           | Tipo           | Descrição                          |
|-----------------|----------------|------------------------------------|
| id              | bigint         | Chave primária                     |
| tenant_id       | FK tenants     | Empresa dona                       |
| board_id        | FK boards      | Quadro dono                        |
| column_id       | FK columns     | Coluna onde está                   |
| user_id         | FK users       | Responsável pelo card              |
| title           | string         | Título da tarefa                   |
| description     | text           | Descrição da tarefa                |
| position        | integer        | Ordem na coluna                    |
| due_date        | date           | Data de vencimento                 |
| priority        | enum           | low/medium/high                    |
| color           | string         | Cor do card                        |
| icon            | string         | Ícone do card                      |
| is_archived     | boolean        | Arquivado                          |
| slug            | string         | Slug único                         |
| background_image| string         | Imagem de fundo                    |
| is_template     | boolean        | É modelo                           |
| is_read_only    | boolean        | Somente leitura                    |
| is_collaborative| boolean        | Colaborativo                       |
| is_public       | boolean        | Público                            |
| is_private      | boolean        | Privado                            |
| deleted_at      | timestamp      | Soft delete                        |
| timestamps      | timestamps     | Criação/atualização                |

---

## 💬 **card_comments**

Comentários nos cards.

| Campo    | Tipo         | Descrição                 |
|----------|--------------|---------------------------|
| id       | bigint       | Chave primária            |
| card_id  | FK cards     | Card relacionado          |
| user_id  | FK users     | Autor do comentário       |
| comment  | text         | Conteúdo do comentário    |
| timestamps| timestamps  | Criação/atualização       |

---

## ✅ **checklists**

Checklist de tarefas dentro de um card.

| Campo        | Tipo       | Descrição                 |
|--------------|------------|---------------------------|
| id           | bigint     | Chave primária            |
| card_id      | FK cards   | Card relacionado          |
| title        | string     | Título da checklist       |
| is_completed | boolean    | Concluída ou não          |
| timestamps   | timestamps | Criação/atualização       |

---

## 📎 **attachments**

Anexos de um card.

| Campo     | Tipo        | Descrição                 |
|-----------|-------------|---------------------------|
| id        | bigint      | Chave primária            |
| card_id   | FK cards    | Card relacionado          |
| user_id   | FK users    | Autor do anexo            |
| filename  | string      | Nome do arquivo           |
| path      | string      | Caminho do arquivo        |
| timestamps| timestamps  | Criação/atualização       |

---

## ⚙️ **cache**

Tabela padrão para cache de dados.

| Campo      | Tipo    | Descrição                 |
|------------|---------|---------------------------|
| key        | string  | Chave primária            |
| value      | text    | Valor armazenado          |
| expiration | integer | Tempo de expiração        |

---

## 🔒 **cache_locks**

Locks do cache.

| Campo      | Tipo    | Descrição                 |
|------------|---------|---------------------------|
| key        | string  | Chave primária            |
| owner      | string  | Dono do lock              |
| expiration | integer | Tempo de expiração        |

---

## ⚙️ **jobs**

Gerencia filas de jobs.

| Campo       | Tipo    | Descrição                 |
|-------------|---------|---------------------------|
| id          | bigint  | Chave primária            |
| queue       | string  | Fila                      |
| payload     | text    | Payload serializado       |
| attempts    | int     | Tentativas                |
| reserved_at | int     | Data reservada            |
| available_at| int     | Data disponível           |
| created_at  | int     | Criação                   |

---

## 🧩 **job_batches**

Gerencia lotes de jobs.

| Campo         | Tipo    | Descrição                 |
|---------------|---------|---------------------------|
| id            | string  | Chave primária            |
| name          | string  | Nome do lote              |
| total_jobs    | int     | Total de jobs             |
| pending_jobs  | int     | Jobs pendentes            |
| failed_jobs   | int     | Jobs falhos               |
| failed_job_ids| text    | IDs dos jobs falhos       |
| options       | text    | Opções                    |
| cancelled_at  | int     | Cancelado em              |
| created_at    | int     | Criado em                 |
| finished_at   | int     | Finalizado em             |

---

## ⚙️ **failed_jobs**

Registra jobs com falha.

| Campo      | Tipo    | Descrição                 |
|------------|---------|---------------------------|
| id         | bigint  | Chave primária            |
| uuid       | string  | UUID único                |
| connection | text    | Conexão                   |
| queue      | text    | Fila                      |
| payload    | text    | Payload serializado       |
| exception  | text    | Exceção capturada         |
| failed_at  | timestamp | Data da falha           |

---

## ✅ **Está tudo conectado!**

- Relacionamentos bem definidos
- Multi-tenant estruturado
- Boards, Colunas, Cards, Comentários, Checklists, Anexos — 🔥

---