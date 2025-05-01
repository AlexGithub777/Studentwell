-- Create Users table
CREATE TABLE Users (
    UserID INT NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(30) NOT NULL,
    LastName VARCHAR(30) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Password VARCHAR(50) NOT NULL,
    Role VARCHAR(15) NOT NULL DEFAULT 'Student',
    JoinDateTime TIMESTAMP NOT NULL,
    PRIMARY KEY (UserID)
);

-- Create Goals table
CREATE TABLE Goals (
    GoalID INT NOT NULL AUTO_INCREMENT,
    UserID INT NOT NULL,
    GoalTitle VARCHAR(30) NOT NULL,
    GoalCategory VARCHAR(20) NOT NULL,
    GoalStartDate DATE NOT NULL,
    GoalTargetDate DATE NOT NULL,
    Notes VARCHAR(255) NULL,
    CreatedAt TIMESTAMP NOT NULL,
    PRIMARY KEY (GoalID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
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
    UserID INT NOT NULL,
    SleepDate DATE NOT NULL,
    SleepDurationMinutes INT(3) NOT NULL,
    SleepQuality INT(1) NOT NULL,
    Notes VARCHAR(255) NULL,
    LoggedAt TIMESTAMP NOT NULL,
    PRIMARY KEY (SleepLogID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create Mood_Logs table
CREATE TABLE Mood_Logs (
    MoodLogID INT NOT NULL AUTO_INCREMENT,
    UserID INT NOT NULL,
    MoodRating INT(1) NOT NULL,
    Emotions JSON NULL,
    Reflection VARCHAR(255) NULL,
    LoggedAt TIMESTAMP NOT NULL,
    PRIMARY KEY (MoodLogID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create Planned_Exercises table
CREATE TABLE Planned_Exercises (
    PlannedExerciseID INT NOT NULL AUTO_INCREMENT,
    UserID INT NOT NULL,
    ExerciseDateTime DATETIME NOT NULL,
    ExerciseType VARCHAR(20) NOT NULL,
    ExerciseIntensity VARCHAR(25) NOT NULL,
    DurationMinutes INT(4) NOT NULL,
    Notes VARCHAR(255) NULL,
    PlannedAt TIMESTAMP NOT NULL,
    PRIMARY KEY (PlannedExerciseID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create Logged_Exercises table
CREATE TABLE Logged_Exercises (
    LoggedExerciseID INT NOT NULL AUTO_INCREMENT,
    UserID INT NOT NULL,
    PlannedExerciseID INT NULL,
    Status VARCHAR(15) NOT NULL,
    ExerciseDateTime DATETIME NOT NULL,
    ExerciseType VARCHAR(20) NOT NULL,
    ExerciseIntensity VARCHAR(25) NOT NULL,
    DurationMinutes INT(4) NOT NULL,
    Notes VARCHAR(255) NULL,
    LoggedAt TIMESTAMP NOT NULL,
    PRIMARY KEY (LoggedExerciseID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (PlannedExerciseID) REFERENCES Planned_Exercises(PlannedExerciseID)
);

-- Create Forum_Posts table
CREATE TABLE Forum_Posts (
    ForumPostID INT NOT NULL AUTO_INCREMENT,
    UserID INT NOT NULL,
    PostTitle VARCHAR(50) NOT NULL,
    PostCategory VARCHAR(20) NOT NULL,
    Content VARCHAR(500) NOT NULL,
    PostLikes INT NOT NULL DEFAULT 0,
    CreatedAt TIMESTAMP NOT NULL,
    PRIMARY KEY (ForumPostID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create Forum_Replies table
CREATE TABLE Forum_Replies (
    ForumPostID INT NOT NULL AUTO_INCREMENT,
    UserID INT NOT NULL,
    PostID INT NOT NULL,
    Content VARCHAR(500) NOT NULL,
    ReplyLikes INT NOT NULL DEFAULT 0,
    CreatedAt TIMESTAMP NOT NULL,
    PRIMARY KEY (ForumPostID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (PostID) REFERENCES Forum_Posts(ForumPostID)
);

-- Create Support_Resources table
CREATE TABLE Support_Resources (
    SupportResourceID INT NOT NULL AUTO_INCREMENT,
    ResourceTitle VARCHAR(50) NOT NULL,
    ResourceCategory VARCHAR(25) NOT NULL,
    Phone INT(13) NULL,
    Location VARCHAR(100) NULL,
    Description VARCHAR(150) NOT NULL,
    PRIMARY KEY (SupportResourceID)
);