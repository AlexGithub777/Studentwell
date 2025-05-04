-- Create Goals table
CREATE TABLE Goals (
    GoalID INT NOT NULL AUTO_INCREMENT,
    UserID BIGINT UNSIGNED NOT NULL,
    GoalTitle VARCHAR(30) NOT NULL,
    GoalCategory VARCHAR(20) NOT NULL,
    GoalStartDate DATE NOT NULL,
    GoalTargetDate DATE NOT NULL,
    Notes VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL,
    PRIMARY KEY (GoalID),
    FOREIGN KEY (UserID) REFERENCES users(id)
);

-- Create Goal_Logs table
CREATE TABLE Goal_Logs (
    GoalLogID INT NOT NULL AUTO_INCREMENT,
    GoalID INT NOT NULL,
    GoalLogDate DATE NOT NULL,
    GoalStatus VARCHAR(15) NOT NULL,
    Notes VARCHAR(255) NOT NULL,
    PRIMARY KEY (GoalLogID),
    FOREIGN KEY (GoalID) REFERENCES Goals(GoalID)
);

-- Create Sleep_Logs table
CREATE TABLE Sleep_Logs (
    SleepLogID INT NOT NULL AUTO_INCREMENT,
    UserID BIGINT UNSIGNED NOT NULL,
    SleepDate DATE NOT NULL,
    SleepDurationMinutes INT NOT NULL,
    SleepQuality INT NOT NULL,
    Notes VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL,
    PRIMARY KEY (SleepLogID),
    FOREIGN KEY (UserID) REFERENCES users(id)
);

-- Create Mood_Logs table
CREATE TABLE Mood_Logs (
    MoodLogID INT NOT NULL AUTO_INCREMENT,
    UserID BIGINT UNSIGNED NOT NULL,
    MoodRating INT NOT NULL,
    Emotions JSON NULL,
    Reflection VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL,
    PRIMARY KEY (MoodLogID),
    FOREIGN KEY (UserID) REFERENCES users(id)
);

-- Create Planned_Exercises table
CREATE TABLE Planned_Exercises (
    PlannedExerciseID INT NOT NULL AUTO_INCREMENT,
    UserID BIGINT UNSIGNED NOT NULL,
    ExerciseDateTime DATETIME NOT NULL,
    ExerciseType VARCHAR(20) NOT NULL,
    ExerciseIntensity VARCHAR(25) NOT NULL,
    DurationMinutes INT NOT NULL,
    Notes VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL,
    PRIMARY KEY (PlannedExerciseID),
    FOREIGN KEY (UserID) REFERENCES users(id)
);

-- Create Logged_Exercises table
CREATE TABLE Logged_Exercises (
    LoggedExerciseID INT NOT NULL AUTO_INCREMENT,
    UserID BIGINT UNSIGNED NOT NULL,
    PlannedExerciseID INT NULL,
    Status VARCHAR(15) NOT NULL,
    ExerciseDateTime DATETIME NOT NULL,
    ExerciseType VARCHAR(20) NOT NULL,
    ExerciseIntensity VARCHAR(25) NOT NULL,
    DurationMinutes INT NOT NULL,
    Notes VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL,
    PRIMARY KEY (LoggedExerciseID),
    FOREIGN KEY (UserID) REFERENCES users(id),
    FOREIGN KEY (PlannedExerciseID) REFERENCES Planned_Exercises(PlannedExerciseID)
);

-- Create Forum_Posts table
CREATE TABLE Forum_Posts (
    ForumPostID INT NOT NULL AUTO_INCREMENT,
    UserID BIGINT UNSIGNED NOT NULL,
    PostTitle VARCHAR(50) NOT NULL,
    PostCategory VARCHAR(20) NOT NULL,
    Content VARCHAR(500) NOT NULL,
    PostLikes INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (ForumPostID),
    FOREIGN KEY (UserID) REFERENCES users(id)
);

-- Create Forum_Replies table
CREATE TABLE Forum_Replies (
    ReplyID INT NOT NULL AUTO_INCREMENT,
    UserID BIGINT UNSIGNED NOT NULL,
    PostID INT NOT NULL,
    Content VARCHAR(500) NOT NULL,
    ReplyLikes INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL,
    PRIMARY KEY (ReplyID),
    FOREIGN KEY (UserID) REFERENCES users(id),
    FOREIGN KEY (PostID) REFERENCES Forum_Posts(ForumPostID)
);

-- Create Support_Resources table
CREATE TABLE Support_Resources (
    SupportResourceID INT NOT NULL AUTO_INCREMENT,
    ResourceTitle VARCHAR(50) NOT NULL,
    ResourceCategory VARCHAR(25) NOT NULL,
    Phone VARCHAR(13) NULL,
    Location VARCHAR(100) NULL,
    Description VARCHAR(150) NOT NULL,
    PRIMARY KEY (SupportResourceID)
);

-- Create Resource_Categories table
CREATE TABLE Resource_Categories (
    ResourceCategoryID INT NOT NULL AUTO_INCREMENT,
    Name VARCHAR(50) NOT NULL,
    PRIMARY KEY (ResourceCategoryID)
);
