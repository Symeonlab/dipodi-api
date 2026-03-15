# Dipoddi API - iOS Integration Guide

## Table of Contents
1. [Overview](#overview)
2. [Base Configuration](#base-configuration)
3. [Authentication](#authentication)
4. [API Client Setup](#api-client-setup)
5. [Models](#models)
6. [API Endpoints](#api-endpoints)
7. [Localization](#localization)
8. [Error Handling](#error-handling)
9. [Best Practices](#best-practices)

---

## Overview

The Dipoddi API is a RESTful API built with Laravel that provides endpoints for:
- User authentication (email/password + social login)
- User profile management
- Workout plan generation
- Nutrition plan generation
- Goals tracking
- Achievements system
- Posts/Feed content
- Progress logging
- Kine (physiotherapy) exercises

**Base URL:** `https://your-domain.com/api`

**API Version:** v1 (implicit)

---

## Base Configuration

### Info.plist Configuration

```xml
<!-- Allow HTTP for development -->
<key>NSAppTransportSecurity</key>
<dict>
    <key>NSAllowsArbitraryLoads</key>
    <false/>
    <key>NSExceptionDomains</key>
    <dict>
        <key>localhost</key>
        <dict>
            <key>NSExceptionAllowsInsecureHTTPLoads</key>
            <true/>
        </dict>
    </dict>
</dict>
```

### Constants.swift

```swift
import Foundation

enum APIConstants {
    static let baseURL = "https://your-domain.com/api"

    // Timeouts
    static let requestTimeout: TimeInterval = 30
    static let resourceTimeout: TimeInterval = 60

    // Rate Limits
    static let standardRateLimit = 60  // requests per minute
    static let heavyRateLimit = 10     // requests per minute for expensive operations
    static let authRateLimit = 5       // requests per minute for auth endpoints
}

enum APIEndpoints {
    // Auth
    static let register = "/auth/register"
    static let login = "/auth/login"
    static let logout = "/auth/logout"
    static let forgotPassword = "/auth/forgot-password"
    static func socialLogin(provider: String) -> String { "/auth/\(provider)/login" }

    // User
    static let user = "/user"
    static let userProfile = "/user/profile"
    static let userProgress = "/user-progress"

    // Dashboard
    static let dashboardMetrics = "/dashboard-metrics"

    // Onboarding
    static let onboardingData = "/onboarding-data"

    // Workout
    static let workoutPlan = "/workout-plan"
    static let workoutPlanGenerate = "/workout-plan/generate"

    // Nutrition
    static let nutritionPlan = "/nutrition-plan"

    // Kine
    static let kineData = "/kine-data"
    static let kineFavorites = "/kine-favorites"
    static let kineFavoritesToggle = "/kine-favorites/toggle"

    // Goals
    static let goals = "/goals"
    static let activeGoal = "/goals/active"
    static func goal(id: Int) -> String { "/goals/\(id)" }
    static func goalProgress(id: Int) -> String { "/goals/\(id)/progress" }
    static func goalStatus(id: Int) -> String { "/goals/\(id)/status" }

    // Achievements
    static let achievements = "/achievements"
    static let achievementsEarned = "/achievements/earned"
    static let achievementsLeaderboard = "/achievements/leaderboard"
    static func achievement(id: Int) -> String { "/achievements/\(id)" }

    // Posts
    static let posts = "/posts"
    static let postsLatest = "/posts/latest"
    static func post(slug: String) -> String { "/posts/\(slug)" }

    // Settings
    static let reminderSettings = "/settings/reminders"

    // Export
    static let exportWorkoutPdf = "/export/workout-plan/pdf"
    static let exportWorkoutHtml = "/export/workout-plan/html"
}
```

---

## Authentication

### Token Storage (Keychain)

```swift
import Security
import Foundation

class KeychainManager {
    static let shared = KeychainManager()
    private let service = "com.dipodi.app"

    private init() {}

    func saveToken(_ token: String) throws {
        let data = token.data(using: .utf8)!

        let query: [String: Any] = [
            kSecClass as String: kSecClassGenericPassword,
            kSecAttrService as String: service,
            kSecAttrAccount as String: "authToken",
            kSecValueData as String: data
        ]

        // Delete existing item
        SecItemDelete(query as CFDictionary)

        // Add new item
        let status = SecItemAdd(query as CFDictionary, nil)
        guard status == errSecSuccess else {
            throw KeychainError.saveFailed
        }
    }

    func getToken() -> String? {
        let query: [String: Any] = [
            kSecClass as String: kSecClassGenericPassword,
            kSecAttrService as String: service,
            kSecAttrAccount as String: "authToken",
            kSecReturnData as String: true
        ]

        var result: AnyObject?
        let status = SecItemCopyMatching(query as CFDictionary, &result)

        guard status == errSecSuccess,
              let data = result as? Data,
              let token = String(data: data, encoding: .utf8) else {
            return nil
        }

        return token
    }

    func deleteToken() {
        let query: [String: Any] = [
            kSecClass as String: kSecClassGenericPassword,
            kSecAttrService as String: service,
            kSecAttrAccount as String: "authToken"
        ]
        SecItemDelete(query as CFDictionary)
    }
}

enum KeychainError: Error {
    case saveFailed
    case readFailed
}
```

### Authentication Manager

```swift
import Foundation
import Combine

class AuthManager: ObservableObject {
    static let shared = AuthManager()

    @Published var isAuthenticated = false
    @Published var currentUser: User?

    private init() {
        isAuthenticated = KeychainManager.shared.getToken() != nil
    }

    func login(email: String, password: String) async throws -> User {
        let response: AuthResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.login,
            method: .post,
            body: LoginRequest(email: email, password: password)
        )

        try KeychainManager.shared.saveToken(response.token)

        await MainActor.run {
            self.isAuthenticated = true
            self.currentUser = response.user
        }

        return response.user
    }

    func register(name: String, email: String, password: String) async throws -> User {
        let response: AuthResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.register,
            method: .post,
            body: RegisterRequest(name: name, email: email, password: password, password_confirmation: password)
        )

        try KeychainManager.shared.saveToken(response.token)

        await MainActor.run {
            self.isAuthenticated = true
            self.currentUser = response.user
        }

        return response.user
    }

    func socialLogin(provider: SocialProvider, token: String) async throws -> User {
        let response: AuthResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.socialLogin(provider: provider.rawValue),
            method: .post,
            body: SocialLoginRequest(token: token)
        )

        try KeychainManager.shared.saveToken(response.token)

        await MainActor.run {
            self.isAuthenticated = true
            self.currentUser = response.user
        }

        return response.user
    }

    func logout() async throws {
        try await APIClient.shared.request(
            endpoint: APIEndpoints.logout,
            method: .post,
            body: EmptyBody()
        ) as EmptyResponse

        KeychainManager.shared.deleteToken()

        await MainActor.run {
            self.isAuthenticated = false
            self.currentUser = nil
        }
    }
}

enum SocialProvider: String {
    case google
    case facebook
    case apple
}
```

---

## API Client Setup

### Network Layer

```swift
import Foundation

enum HTTPMethod: String {
    case get = "GET"
    case post = "POST"
    case put = "PUT"
    case delete = "DELETE"
}

enum APIError: Error, LocalizedError {
    case invalidURL
    case noData
    case decodingError(Error)
    case serverError(Int, String)
    case networkError(Error)
    case unauthorized
    case rateLimited
    case validationError([String: [String]])

    var errorDescription: String? {
        switch self {
        case .invalidURL:
            return "Invalid URL"
        case .noData:
            return "No data received"
        case .decodingError(let error):
            return "Decoding error: \(error.localizedDescription)"
        case .serverError(let code, let message):
            return "Server error (\(code)): \(message)"
        case .networkError(let error):
            return "Network error: \(error.localizedDescription)"
        case .unauthorized:
            return "Session expired. Please login again."
        case .rateLimited:
            return "Too many requests. Please wait a moment."
        case .validationError(let errors):
            return errors.values.flatMap { $0 }.joined(separator: "\n")
        }
    }
}

class APIClient {
    static let shared = APIClient()

    private let session: URLSession
    private let decoder: JSONDecoder
    private let encoder: JSONEncoder

    private init() {
        let config = URLSessionConfiguration.default
        config.timeoutIntervalForRequest = APIConstants.requestTimeout
        config.timeoutIntervalForResource = APIConstants.resourceTimeout

        session = URLSession(configuration: config)

        decoder = JSONDecoder()
        decoder.keyDecodingStrategy = .convertFromSnakeCase
        decoder.dateDecodingStrategy = .custom { decoder in
            let container = try decoder.singleValueContainer()
            let dateString = try container.decode(String.self)

            // Try ISO8601 first
            if let date = ISO8601DateFormatter().date(from: dateString) {
                return date
            }

            // Try yyyy-MM-dd format
            let formatter = DateFormatter()
            formatter.dateFormat = "yyyy-MM-dd"
            if let date = formatter.date(from: dateString) {
                return date
            }

            throw DecodingError.dataCorruptedError(in: container, debugDescription: "Invalid date format")
        }

        encoder = JSONEncoder()
        encoder.keyEncodingStrategy = .convertToSnakeCase
    }

    func request<T: Decodable, B: Encodable>(
        endpoint: String,
        method: HTTPMethod = .get,
        body: B? = nil as EmptyBody?,
        queryParams: [String: String]? = nil
    ) async throws -> T {
        // Build URL
        var urlComponents = URLComponents(string: APIConstants.baseURL + endpoint)

        if let queryParams = queryParams {
            urlComponents?.queryItems = queryParams.map { URLQueryItem(name: $0.key, value: $0.value) }
        }

        guard let url = urlComponents?.url else {
            throw APIError.invalidURL
        }

        // Build request
        var request = URLRequest(url: url)
        request.httpMethod = method.rawValue
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        request.setValue("application/json", forHTTPHeaderField: "Accept")

        // Add locale header
        let locale = Locale.current.language.languageCode?.identifier ?? "en"
        let supportedLocale = ["en", "fr", "ar"].contains(locale) ? locale : "en"
        request.setValue(supportedLocale, forHTTPHeaderField: "Accept-Language")

        // Add auth token
        if let token = KeychainManager.shared.getToken() {
            request.setValue("Bearer \(token)", forHTTPHeaderField: "Authorization")
        }

        // Add body
        if let body = body, !(body is EmptyBody) {
            request.httpBody = try encoder.encode(body)
        }

        // Execute request
        let (data, response) = try await session.data(for: request)

        guard let httpResponse = response as? HTTPURLResponse else {
            throw APIError.noData
        }

        // Handle response codes
        switch httpResponse.statusCode {
        case 200...299:
            do {
                return try decoder.decode(T.self, from: data)
            } catch {
                throw APIError.decodingError(error)
            }

        case 401:
            // Token expired - clear and notify
            KeychainManager.shared.deleteToken()
            await MainActor.run {
                AuthManager.shared.isAuthenticated = false
            }
            throw APIError.unauthorized

        case 422:
            // Validation error
            if let errorResponse = try? decoder.decode(ValidationErrorResponse.self, from: data) {
                throw APIError.validationError(errorResponse.errors)
            }
            throw APIError.serverError(422, "Validation failed")

        case 429:
            throw APIError.rateLimited

        default:
            if let errorResponse = try? decoder.decode(ErrorResponse.self, from: data) {
                throw APIError.serverError(httpResponse.statusCode, errorResponse.message)
            }
            throw APIError.serverError(httpResponse.statusCode, "Unknown error")
        }
    }
}

// Empty body for requests without body
struct EmptyBody: Encodable {}
struct EmptyResponse: Decodable {}

struct ErrorResponse: Decodable {
    let success: Bool
    let message: String
}

struct ValidationErrorResponse: Decodable {
    let success: Bool
    let message: String
    let errors: [String: [String]]
}
```

---

## Models

### User Models

```swift
import Foundation

struct User: Codable, Identifiable {
    let id: Int
    let name: String
    let email: String
    let role: String?
    let profile: UserProfile?
    let createdAt: Date?
}

struct UserProfile: Codable {
    let id: Int?
    let userId: Int?
    let discipline: Discipline?
    let position: String?
    let goal: String?
    let gender: Gender?
    let age: Int?
    let weight: Double?
    let height: Double?
    let waist: Double?
    let chest: Double?
    let hips: Double?
    let idealWeight: Double?
    let isOnboardingComplete: Bool?
    let allergies: [String]?
    let injuries: [String]?
    let medicalConditions: [String]?
}

enum Discipline: String, Codable, CaseIterable {
    case football = "FOOTBALL"
    case fitness = "FITNESS"
    case padel = "PADEL"
}

enum Gender: String, Codable {
    case male = "male"
    case female = "female"
}

struct AuthResponse: Decodable {
    let success: Bool
    let token: String
    let user: User
}

struct LoginRequest: Encodable {
    let email: String
    let password: String
}

struct RegisterRequest: Encodable {
    let name: String
    let email: String
    let password: String
    let passwordConfirmation: String
}

struct SocialLoginRequest: Encodable {
    let token: String
}
```

### Goal Models

```swift
import Foundation

struct Goal: Codable, Identifiable {
    let id: Int
    let goalType: GoalType
    let goalTypeLabel: String?
    let status: GoalStatus
    let progress: Double
    let expectedProgress: Double?
    let isOnTrack: Bool?
    let targetWeight: Double?
    let targetWaist: Double?
    let targetChest: Double?
    let targetHips: Double?
    let startWeight: Double?
    let startWaist: Double?
    let targetWorkoutsPerWeek: Int?
    let startDate: String?
    let targetDate: String?
    let completedAt: String?
    let weeksCompleted: Int?
    let totalWeeks: Int?
    let achievements: [String]?
    let notes: String?
    let createdAt: String?
}

enum GoalType: String, Codable, CaseIterable {
    case weightLoss = "weight_loss"
    case muscleGain = "muscle_gain"
    case maintain = "maintain"
    case custom = "custom"

    var displayName: String {
        switch self {
        case .weightLoss: return NSLocalizedString("Weight Loss", comment: "")
        case .muscleGain: return NSLocalizedString("Muscle Gain", comment: "")
        case .maintain: return NSLocalizedString("Maintain Shape", comment: "")
        case .custom: return NSLocalizedString("Custom Goal", comment: "")
        }
    }
}

enum GoalStatus: String, Codable {
    case active
    case completed
    case paused
    case abandoned
}

struct CreateGoalRequest: Encodable {
    let goalType: String
    let targetWeight: Double?
    let targetWaist: Double?
    let targetChest: Double?
    let targetHips: Double?
    let targetWorkoutsPerWeek: Int?
    let totalWeeks: Int?
    let targetDate: String?
    let notes: String?
}

struct UpdateGoalStatusRequest: Encodable {
    let status: String
}

struct GoalProgressResponse: Decodable {
    let success: Bool
    let message: String?
    let data: GoalProgressData?
}

struct GoalProgressData: Decodable {
    let progress: Double
    let weeksCompleted: Int
    let status: GoalStatus
    let newAchievements: [String]?
}
```

### Achievement Models

```swift
import Foundation

struct Achievement: Codable, Identifiable {
    let id: Int
    let key: String
    let name: String
    let description: String
    let icon: String?
    let points: Int
    let category: AchievementCategory
    let earned: Bool?
    let earnedAt: String?
    let earnedByCount: Int?
}

enum AchievementCategory: String, Codable {
    case workout
    case consistency
    case milestone
    case nutrition
    case special
}

struct AchievementsResponse: Decodable {
    let success: Bool
    let data: AchievementsData
}

struct AchievementsData: Decodable {
    let achievements: [Achievement]
    let byCategory: [String: [Achievement]]?
    let totalPoints: Int
    let totalEarned: Int
    let totalAvailable: Int
}

struct LeaderboardResponse: Decodable {
    let success: Bool
    let data: LeaderboardData
}

struct LeaderboardData: Decodable {
    let leaderboard: [LeaderboardEntry]
    let currentUser: CurrentUserRank
}

struct LeaderboardEntry: Decodable, Identifiable {
    var id: Int { self.userId }
    let userId: Int
    let name: String
    let totalPoints: Int
    let achievementCount: Int

    enum CodingKeys: String, CodingKey {
        case userId = "id"
        case name
        case totalPoints = "total_points"
        case achievementCount = "achievement_count"
    }
}

struct CurrentUserRank: Decodable {
    let rank: Int
    let totalPoints: Int
    let achievementCount: Int
}
```

### Post Models

```swift
import Foundation

struct Post: Codable, Identifiable {
    let id: Int
    let title: String
    let content: String?
    let excerpt: String?
    let slug: String
    let featuredImage: String?
    let author: String?
    let publishedAt: String
    let readingTime: Int?
}

struct PostsResponse: Decodable {
    let success: Bool
    let data: [Post]
    let meta: PaginationMeta?
}

struct PaginationMeta: Decodable {
    let currentPage: Int
    let lastPage: Int
    let perPage: Int
    let total: Int
}
```

### Dashboard Models

```swift
import Foundation

struct DashboardResponse: Decodable {
    let success: Bool
    let stats: DashboardStats
    let chart: ChartData
    let myLatestProgress: [UserProgress]
    let activeGoal: ActiveGoalData?
    let achievements: AchievementSummary
    let latestPosts: [PostSummary]
}

struct DashboardStats: Decodable {
    let totalUsers: Int
    let newUsersWeek: Int
    let totalProgressLogs: Int
    let publishedPosts: Int
}

struct ChartData: Decodable {
    let labels: [String]
    let data: [Int]
}

struct ActiveGoalData: Decodable {
    let id: Int
    let goalType: GoalType
    let progress: Double
    let isOnTrack: Bool
    let weeksCompleted: Int
    let totalWeeks: Int
    let targetDate: String?
}

struct AchievementSummary: Decodable {
    let totalEarned: Int
    let totalPoints: Int
    let recent: [RecentAchievement]
}

struct RecentAchievement: Decodable, Identifiable {
    let id: Int
    let name: String
    let icon: String?
    let points: Int
    let earnedAt: String?
}

struct PostSummary: Decodable, Identifiable {
    let id: Int
    let title: String
    let slug: String
    let featuredImage: String?
    let publishedAt: String
}

struct UserProgress: Codable, Identifiable {
    let id: Int
    let date: String
    let weight: Double?
    let waist: Double?
    let chest: Double?
    let hips: Double?
    let mood: String?
    let workoutCompleted: String?
    let notes: String?
}
```

### Workout Models

```swift
import Foundation

struct WorkoutSession: Codable, Identifiable {
    let id: Int
    let day: String
    let theme: String
    let warmup: String?
    let finisher: String?
    let exercises: [WorkoutExercise]
    let createdAt: String?
}

struct WorkoutExercise: Codable, Identifiable {
    let id: Int
    let name: String
    let sets: Int
    let reps: String
    let recovery: String
    let videoUrl: String?
    let description: String?
}

struct WorkoutPlanResponse: Decodable {
    let success: Bool
    let data: [WorkoutSession]?
    let message: String?
}

struct GenerateWorkoutRequest: Encodable {
    let forceRegenerate: Bool?
}
```

---

## API Endpoints

### Services Layer

```swift
import Foundation

// MARK: - Goals Service

class GoalsService {
    static let shared = GoalsService()

    func fetchAllGoals() async throws -> [Goal] {
        let response: APIResponse<[Goal]> = try await APIClient.shared.request(
            endpoint: APIEndpoints.goals,
            method: .get
        )
        return response.data
    }

    func fetchActiveGoal() async throws -> Goal? {
        let response: APIResponse<Goal?> = try await APIClient.shared.request(
            endpoint: APIEndpoints.activeGoal,
            method: .get
        )
        return response.data
    }

    func createGoal(_ request: CreateGoalRequest) async throws -> Goal {
        let response: APIResponse<Goal> = try await APIClient.shared.request(
            endpoint: APIEndpoints.goals,
            method: .post,
            body: request
        )
        return response.data
    }

    func updateProgress(goalId: Int) async throws -> GoalProgressData {
        let response: GoalProgressResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.goalProgress(id: goalId),
            method: .post,
            body: EmptyBody()
        )
        return response.data!
    }

    func updateStatus(goalId: Int, status: GoalStatus) async throws {
        let _: EmptyResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.goalStatus(id: goalId),
            method: .put,
            body: UpdateGoalStatusRequest(status: status.rawValue)
        )
    }
}

// MARK: - Achievements Service

class AchievementsService {
    static let shared = AchievementsService()

    func fetchAll() async throws -> AchievementsData {
        let response: AchievementsResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.achievements,
            method: .get
        )
        return response.data
    }

    func fetchEarned() async throws -> [Achievement] {
        let response: APIResponse<[Achievement]> = try await APIClient.shared.request(
            endpoint: APIEndpoints.achievementsEarned,
            method: .get
        )
        return response.data
    }

    func fetchLeaderboard(limit: Int = 10) async throws -> LeaderboardData {
        let response: LeaderboardResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.achievementsLeaderboard,
            method: .get,
            queryParams: ["limit": String(limit)]
        )
        return response.data
    }
}

// MARK: - Posts Service

class PostsService {
    static let shared = PostsService()

    func fetchPosts(page: Int = 1, perPage: Int = 10) async throws -> (posts: [Post], meta: PaginationMeta?) {
        let response: PostsResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.posts,
            method: .get,
            queryParams: ["page": String(page), "per_page": String(perPage)]
        )
        return (response.data, response.meta)
    }

    func fetchLatest(limit: Int = 5) async throws -> [Post] {
        let response: APIResponse<[Post]> = try await APIClient.shared.request(
            endpoint: APIEndpoints.postsLatest,
            method: .get,
            queryParams: ["limit": String(limit)]
        )
        return response.data
    }

    func fetchPost(slug: String) async throws -> Post {
        let response: APIResponse<Post> = try await APIClient.shared.request(
            endpoint: APIEndpoints.post(slug: slug),
            method: .get
        )
        return response.data
    }
}

// MARK: - Dashboard Service

class DashboardService {
    static let shared = DashboardService()

    func fetchMetrics() async throws -> DashboardResponse {
        return try await APIClient.shared.request(
            endpoint: APIEndpoints.dashboardMetrics,
            method: .get
        )
    }
}

// MARK: - Workout Service

class WorkoutService {
    static let shared = WorkoutService()

    func fetchWeeklyPlan() async throws -> [WorkoutSession] {
        let response: WorkoutPlanResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.workoutPlan,
            method: .get
        )
        return response.data ?? []
    }

    func generatePlan(forceRegenerate: Bool = false) async throws -> [WorkoutSession] {
        let response: WorkoutPlanResponse = try await APIClient.shared.request(
            endpoint: APIEndpoints.workoutPlanGenerate,
            method: .post,
            body: GenerateWorkoutRequest(forceRegenerate: forceRegenerate)
        )
        return response.data ?? []
    }
}

// MARK: - Generic API Response

struct APIResponse<T: Decodable>: Decodable {
    let success: Bool
    let data: T
    let message: String?
}
```

---

## Localization

### Setting Up Localization

The API supports three languages: English (en), French (fr), and Arabic (ar).

```swift
// LocalizationManager.swift

import Foundation

class LocalizationManager: ObservableObject {
    static let shared = LocalizationManager()

    @Published var currentLocale: SupportedLocale = .english

    enum SupportedLocale: String, CaseIterable {
        case english = "en"
        case french = "fr"
        case arabic = "ar"

        var displayName: String {
            switch self {
            case .english: return "English"
            case .french: return "Français"
            case .arabic: return "العربية"
            }
        }

        var isRTL: Bool {
            self == .arabic
        }
    }

    private init() {
        // Load saved preference or use system locale
        if let saved = UserDefaults.standard.string(forKey: "appLocale"),
           let locale = SupportedLocale(rawValue: saved) {
            currentLocale = locale
        } else {
            let systemLocale = Locale.current.language.languageCode?.identifier ?? "en"
            currentLocale = SupportedLocale(rawValue: systemLocale) ?? .english
        }
    }

    func setLocale(_ locale: SupportedLocale) {
        currentLocale = locale
        UserDefaults.standard.set(locale.rawValue, forKey: "appLocale")
    }
}
```

### RTL Support for Arabic

```swift
// In your App struct or main view

import SwiftUI

@main
struct DipoddiApp: App {
    @StateObject private var localization = LocalizationManager.shared

    var body: some Scene {
        WindowGroup {
            ContentView()
                .environment(\.layoutDirection, localization.currentLocale.isRTL ? .rightToLeft : .leftToRight)
                .environmentObject(localization)
        }
    }
}
```

---

## Error Handling

### Centralized Error Handling

```swift
import SwiftUI

class ErrorHandler: ObservableObject {
    static let shared = ErrorHandler()

    @Published var currentError: AppError?
    @Published var showError = false

    func handle(_ error: Error) {
        let appError: AppError

        if let apiError = error as? APIError {
            appError = AppError(from: apiError)
        } else {
            appError = AppError(
                title: "Error",
                message: error.localizedDescription,
                recoveryAction: nil
            )
        }

        DispatchQueue.main.async {
            self.currentError = appError
            self.showError = true
        }
    }
}

struct AppError: Identifiable {
    let id = UUID()
    let title: String
    let message: String
    let recoveryAction: (() -> Void)?

    init(title: String, message: String, recoveryAction: (() -> Void)? = nil) {
        self.title = title
        self.message = message
        self.recoveryAction = recoveryAction
    }

    init(from apiError: APIError) {
        switch apiError {
        case .unauthorized:
            self.title = NSLocalizedString("Session Expired", comment: "")
            self.message = NSLocalizedString("Please login again.", comment: "")
            self.recoveryAction = {
                // Navigate to login
            }
        case .rateLimited:
            self.title = NSLocalizedString("Please Wait", comment: "")
            self.message = NSLocalizedString("Too many requests. Try again in a moment.", comment: "")
            self.recoveryAction = nil
        case .validationError(let errors):
            self.title = NSLocalizedString("Validation Error", comment: "")
            self.message = errors.values.flatMap { $0 }.joined(separator: "\n")
            self.recoveryAction = nil
        case .networkError:
            self.title = NSLocalizedString("Network Error", comment: "")
            self.message = NSLocalizedString("Check your internet connection.", comment: "")
            self.recoveryAction = nil
        default:
            self.title = NSLocalizedString("Error", comment: "")
            self.message = apiError.localizedDescription
            self.recoveryAction = nil
        }
    }
}

// Usage in Views
struct ContentView: View {
    @StateObject private var errorHandler = ErrorHandler.shared

    var body: some View {
        NavigationStack {
            // Your content
        }
        .alert(
            errorHandler.currentError?.title ?? "Error",
            isPresented: $errorHandler.showError,
            presenting: errorHandler.currentError
        ) { error in
            Button("OK") {
                error.recoveryAction?()
            }
        } message: { error in
            Text(error.message)
        }
    }
}
```

---

## Best Practices

### 1. Caching

```swift
import Foundation

class CacheManager {
    static let shared = CacheManager()

    private let cache = NSCache<NSString, CacheEntry>()
    private let fileManager = FileManager.default

    class CacheEntry {
        let data: Data
        let expiry: Date

        init(data: Data, ttl: TimeInterval) {
            self.data = data
            self.expiry = Date().addingTimeInterval(ttl)
        }

        var isExpired: Bool {
            Date() > expiry
        }
    }

    func cache<T: Codable>(_ object: T, forKey key: String, ttl: TimeInterval = 300) {
        guard let data = try? JSONEncoder().encode(object) else { return }
        let entry = CacheEntry(data: data, ttl: ttl)
        cache.setObject(entry, forKey: key as NSString)
    }

    func retrieve<T: Codable>(forKey key: String) -> T? {
        guard let entry = cache.object(forKey: key as NSString),
              !entry.isExpired else {
            return nil
        }
        return try? JSONDecoder().decode(T.self, from: entry.data)
    }

    func invalidate(forKey key: String) {
        cache.removeObject(forKey: key as NSString)
    }

    func invalidateAll() {
        cache.removeAllObjects()
    }
}
```

### 2. Offline Support

```swift
import Network

class NetworkMonitor: ObservableObject {
    static let shared = NetworkMonitor()

    private let monitor = NWPathMonitor()
    private let queue = DispatchQueue(label: "NetworkMonitor")

    @Published var isConnected = true
    @Published var connectionType: ConnectionType = .unknown

    enum ConnectionType {
        case wifi
        case cellular
        case unknown
    }

    private init() {
        monitor.pathUpdateHandler = { [weak self] path in
            DispatchQueue.main.async {
                self?.isConnected = path.status == .satisfied
                self?.connectionType = self?.getConnectionType(path) ?? .unknown
            }
        }
        monitor.start(queue: queue)
    }

    private func getConnectionType(_ path: NWPath) -> ConnectionType {
        if path.usesInterfaceType(.wifi) {
            return .wifi
        } else if path.usesInterfaceType(.cellular) {
            return .cellular
        }
        return .unknown
    }
}
```

### 3. Request Retry Logic

```swift
extension APIClient {
    func requestWithRetry<T: Decodable, B: Encodable>(
        endpoint: String,
        method: HTTPMethod = .get,
        body: B? = nil as EmptyBody?,
        maxRetries: Int = 3,
        retryDelay: TimeInterval = 1.0
    ) async throws -> T {
        var lastError: Error?

        for attempt in 0..<maxRetries {
            do {
                return try await request(endpoint: endpoint, method: method, body: body)
            } catch let error as APIError {
                lastError = error

                // Don't retry for certain errors
                switch error {
                case .unauthorized, .validationError:
                    throw error
                case .rateLimited:
                    // Wait longer for rate limiting
                    try await Task.sleep(nanoseconds: UInt64(5 * 1_000_000_000))
                    continue
                default:
                    if attempt < maxRetries - 1 {
                        try await Task.sleep(nanoseconds: UInt64(retryDelay * Double(attempt + 1) * 1_000_000_000))
                    }
                }
            } catch {
                lastError = error
                if attempt < maxRetries - 1 {
                    try await Task.sleep(nanoseconds: UInt64(retryDelay * Double(attempt + 1) * 1_000_000_000))
                }
            }
        }

        throw lastError ?? APIError.networkError(NSError(domain: "Unknown", code: -1))
    }
}
```

### 4. Image Loading

```swift
import SwiftUI

struct AsyncImageLoader: View {
    let url: String?
    let placeholder: Image

    @State private var loadedImage: UIImage?

    var body: some View {
        Group {
            if let image = loadedImage {
                Image(uiImage: image)
                    .resizable()
                    .aspectRatio(contentMode: .fill)
            } else {
                placeholder
                    .resizable()
                    .aspectRatio(contentMode: .fill)
            }
        }
        .task {
            await loadImage()
        }
    }

    private func loadImage() async {
        guard let urlString = url,
              let url = URL(string: urlString) else { return }

        // Check cache first
        if let cached = ImageCache.shared.get(forKey: urlString) {
            loadedImage = cached
            return
        }

        // Download
        do {
            let (data, _) = try await URLSession.shared.data(from: url)
            if let image = UIImage(data: data) {
                ImageCache.shared.set(image, forKey: urlString)
                await MainActor.run {
                    loadedImage = image
                }
            }
        } catch {
            print("Image load failed: \(error)")
        }
    }
}

class ImageCache {
    static let shared = ImageCache()
    private let cache = NSCache<NSString, UIImage>()

    func get(forKey key: String) -> UIImage? {
        cache.object(forKey: key as NSString)
    }

    func set(_ image: UIImage, forKey key: String) {
        cache.setObject(image, forKey: key as NSString)
    }
}
```

### 5. Pull-to-Refresh Pattern

```swift
struct GoalsListView: View {
    @State private var goals: [Goal] = []
    @State private var isLoading = false

    var body: some View {
        List(goals) { goal in
            GoalRowView(goal: goal)
        }
        .refreshable {
            await loadGoals()
        }
        .task {
            await loadGoals()
        }
    }

    private func loadGoals() async {
        isLoading = true
        defer { isLoading = false }

        do {
            goals = try await GoalsService.shared.fetchAllGoals()
        } catch {
            ErrorHandler.shared.handle(error)
        }
    }
}
```

---

## Rate Limiting Considerations

| Endpoint Category | Rate Limit | Notes |
|------------------|------------|-------|
| Auth endpoints | 5/min | Login, register, forgot password |
| Standard API | 60/min | Most endpoints |
| Heavy endpoints | 10/min | Workout/nutrition generation, PDF export |

Implement exponential backoff when receiving 429 responses.

---

## Security Checklist

- [ ] Store auth tokens in Keychain, never UserDefaults
- [ ] Use HTTPS in production
- [ ] Validate SSL certificates (don't disable ATS in production)
- [ ] Clear sensitive data on logout
- [ ] Implement biometric authentication for returning users
- [ ] Don't log sensitive information
- [ ] Implement certificate pinning for high-security apps

---

## Testing

```swift
// MockAPIClient for testing
class MockAPIClient: APIClientProtocol {
    var mockResponse: Any?
    var mockError: Error?

    func request<T: Decodable, B: Encodable>(
        endpoint: String,
        method: HTTPMethod,
        body: B?
    ) async throws -> T {
        if let error = mockError {
            throw error
        }
        guard let response = mockResponse as? T else {
            throw APIError.noData
        }
        return response
    }
}
```

---

## Contact & Support

For API issues or questions, contact the backend team or open an issue in the repository.
